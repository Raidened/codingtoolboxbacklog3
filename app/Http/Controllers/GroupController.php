<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use App\Services\AIHomogeneousGroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * @var AIHomogeneousGroupService
     */
    protected $aiService;

    /**
     * Constructor
     */
    public function __construct(AIHomogeneousGroupService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
    {
        // Récupérer l'utilisateur actuel et son rôle
        $user = Auth::user();
        $userRole = $user->school()->pivot->role ?? null;
        
        // Différent comportement selon le rôle
        if ($userRole === 'student') {
            // Pour les étudiants, montrer uniquement leurs groupes
            $userGroups = $user->groups()->with(['cohort'])->get();
            return view('pages.groups.student_index', compact('userGroups'));
        } else {
            // Pour les enseignants et les admins, montrer tous les groupes
            $cohorts = Cohort::all();
            $groups = Group::with(['users', 'cohort'])
                ->orderBy('cohort_id')
                ->orderBy('batch_name')
                ->orderBy('name')
                ->get();
            // Group by cohort, then by batch_name
            $groupsByCohort = $groups->groupBy(['cohort_id', 'batch_name']);
            return view('pages.groups.index', compact('cohorts', 'groupsByCohort'));
        }
    }

    public function generate(Request $request)
    {
        // Le middleware est maintenant appliqué au niveau des routes
        
        $request->validate([
            'cohort_id' => 'required|exists:cohorts,id',
            'group_size' => 'required|integer|min:2',
            'batch_name' => 'required|string|max:255',
            'replace_existing' => 'sometimes|boolean',
        ]);
         
        // Retrieve parameters
        $cohortId = $request->input('cohort_id');
        $batchName = $request->input('batch_name');
        $groupSize = $request->input('group_size');

         // Récupérer la promotion
         $cohort = Cohort::findOrFail($cohortId);

         try {
             // Démarrer une transaction pour assurer l'intégrité des données
             DB::beginTransaction();
             
             // Suppression des anciens groupes si l'option est activée
             if ($request->has('replace_existing')) {
                 Group::where('cohort_id', $cohort->id)->delete();
             }
             
             // Génération des groupes via IA uniquement
             $aiGroups = $this->aiService->generateGroups($cohort, $groupSize);
             if (!$aiGroups) {
                 // Annulation et message d'erreur si l'IA a échoué
                 DB::rollBack();
                 return redirect()->back()->withErrors(['error' => 'La génération des groupes via IA a échoué.'])->withInput();
             }
             // Enregistrement des groupes générés par l'IA
             $groups = $this->saveAIGeneratedGroups($aiGroups, $cohort, $batchName);
             
            if (empty($groups)) {
                return redirect()->back()->withErrors(['error' => 'Aucun étudiant trouvé dans cette promotion.']);
            }
            
            // Validation de la transaction
            DB::commit();
            
            // Rediriger vers la page d'index avec un message de succès
            return redirect()->route('groups.index')
                ->with('success', 'Fournée de groupes "' . $batchName . '" créée avec succès.');
                
         } catch (\Exception $e) {
             // Annulation de la transaction en cas d'erreur
             DB::rollBack();
             
             // Log de l'erreur
             logger()->error('Erreur lors de la création des groupes', [
                 'error' => $e->getMessage(),
                 'trace' => $e->getTraceAsString()
             ]);
             
             return redirect()->back()
                 ->withErrors(['error' => 'Une erreur est survenue lors de la création des groupes. Veuillez réessayer.'])
                 ->withInput();
         }
    }

    /**
     * Supprimer une fournée de groupes pour une promotion donnée
     */
    public function deleteBatch(Request $request)
    {
        $request->validate([
            'cohort_id' => 'required|exists:cohorts,id',
            'batch_name' => 'nullable|string|max:255',
        ]);
        $cohortId = $request->input('cohort_id');
        $batchName = $request->input('batch_name');

        $query = Group::where('cohort_id', $cohortId);
        if ($batchName !== null) {
            $query->where('batch_name', $batchName);
        } else {
            $query->whereNull('batch_name');
        }
        $groups = $query->get();
        foreach ($groups as $group) {
            $group->users()->detach();
            $group->delete();
        }
        return redirect()->route('groups.index')->with('info', 'Fournée supprimée avec succès.');
    }

    /**
     * Enregistre les groupes générés par l'IA
     * 
     * @param array $aiGroups
     * @param Cohort $cohort
     * @param string $batchName
     * @return array
     */
    private function saveAIGeneratedGroups(array $aiGroups, Cohort $cohort, string $batchName): array
    {
        $savedGroups = [];
        
        foreach ($aiGroups as $index => $groupData) {
            $group = Group::create([
                'cohort_id' => $cohort->id,
                'batch_name' => $batchName,
                'name' => 'Groupe IA #' . ($index + 1),
                'description' => 'Groupe créé automatiquement par l\'IA pour obtenir une répartition homogène des compétences',
                'is_auto_generated' => true,
                'generation_params' => [
                    'ai_generated' => true,
                    'date_generated' => now()->toDateTimeString(),
                ]
            ]);
            
            // Ajouter chaque étudiant au groupe
            $totalSkill = 0;
            $count = 0;
            
            // Normalize members: AI format ['members'=>...] or fallback plain array
            $members = $groupData['members'] ?? $groupData;
            foreach ($members as $member) {
                UserGroup::create([
                    'user_id' => $member['id'],
                    'group_id' => $group->id
                ]);
                
                $totalSkill += $member['skill'] ?? 0;
                $count++;
            }
            
            // Calculer la moyenne des compétences
            $averageSkill = $count > 0 ? round($totalSkill / $count, 2) : 0;
            
            // Mettre à jour la description du groupe avec la moyenne
            $group->description .= " (moyenne des compétences: $averageSkill/20)";
            $group->save();
            
            $savedGroups[] = $group;
        }
        
        return $savedGroups;
    }
}
