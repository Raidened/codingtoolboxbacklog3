<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    /**
     * Affiche la liste des notes pour les enseignants et admins
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perpage', 10);

        // Filter grades by student name when searching
        $gradesQuery = Grade::with(['user', 'cohort', 'teacher'])
            ->when($search, function ($q, $search) {
                return $q->whereHas('user', function ($u) use ($search) {
                    $u->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
            });

        $grades = $gradesQuery->paginate($perPage)
            ->appends($request->only('search', 'perpage'));

        $cohorts = Cohort::all();
        $students = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'student')
            ->select('users.*')
            ->distinct()
            ->get();

        return view('pages.grades.index', [
            'cohorts' => $cohorts,
            'students' => $students,
            'grades' => $grades,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Affiche le formulaire de création d'une note
     */
    public function create()
    {
        // Récupérer toutes les promotions
        $cohorts = Cohort::all();
        
        // Récupérer tous les étudiants
        $students = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'student')
            ->select('users.*')
            ->get();
        
        return view('pages.grades.create', [
            'cohorts' => $cohorts,
            'students' => $students
        ]);
    }

    /**
     * Enregistre une nouvelle note
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'cohort_id' => 'required|exists:cohorts,id',
            'title' => 'required|string|max:255',
            'value' => 'required|numeric|min:0|max:20',
            'evaluation_date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        // Création de la note
        $grade = new Grade();
        $grade->user_id = $validated['user_id'];
        $grade->cohort_id = $validated['cohort_id'];
        $grade->teacher_id = Auth::id(); // L'enseignant connecté
        $grade->title = $validated['title'];
        $grade->value = $validated['value'];
        $grade->evaluation_date = $validated['evaluation_date'];
        $grade->description = $validated['description'] ?? null;
        $grade->save();
        
        return redirect()->route('grades.index')
            ->with('success', 'La note a été ajoutée avec succès');
    }

    /**
     * Affiche les détails d'une note
     */
    public function show(Grade $grade)
    {
        return view('pages.grades.show', [
            'grade' => $grade
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'une note
     */
    public function edit(Grade $grade)
    {
        // Récupérer toutes les promotions
        $cohorts = Cohort::all();
        
        // Récupérer tous les étudiants
        $students = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'student')
            ->select('users.*')
            ->get();
        
        return view('pages.grades.edit', [
            'grade' => $grade,
            'cohorts' => $cohorts,
            'students' => $students
        ]);
    }

    /**
     * Met à jour une note
     */
    public function update(Request $request, Grade $grade)
    {
        // Validation des données
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'cohort_id' => 'required|exists:cohorts,id',
            'title' => 'required|string|max:255',
            'value' => 'required|numeric|min:0|max:20',
            'evaluation_date' => 'required|date',
            'description' => 'nullable|string',
        ]);
        
        // Mise à jour de la note
        $grade->user_id = $validated['user_id'];
        $grade->cohort_id = $validated['cohort_id'];
        $grade->title = $validated['title'];
        $grade->value = $validated['value'];
        $grade->evaluation_date = $validated['evaluation_date'];
        $grade->description = $validated['description'] ?? null;
        $grade->save();
        
        return redirect()->route('grades.index')
            ->with('success', 'La note a été mise à jour avec succès');
    }

    /**
     * Supprime une note
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        
        return redirect()->route('grades.index')
            ->with('success', 'La note a été supprimée avec succès');
    }

    /**
     * Affiche les notes de l'étudiant connecté
     */
    public function studentGrades()
    {
        // Récupérer l'utilisateur connecté
        $user = Auth::user();
        
        // Récupérer les notes de l'étudiant
        $grades = Grade::where('user_id', $user->id)
            ->with(['cohort', 'teacher'])
            ->get();
            
        // Calculer la moyenne des notes
        $averageScore = $grades->avg('value');
        
        return view('pages.grades.student', [
            'grades' => $grades,
            'averageScore' => $averageScore
        ]);
    }
    
    /**
     * Route AJAX pour récupérer les étudiants d'une promotion
     */
    public function getStudents(Request $request)
    {
        try {
            // Vérifier que la requête est bien une requête AJAX
            if (!$request->ajax()) {
                return response()->json(['error' => 'Requête non-AJAX non autorisée'], 400);
            }
            
            $cohortId = $request->input('cohort_id');
            
            if (!$cohortId) {
                return response()->json(['error' => 'ID de promotion non fourni', 'students' => []], 400);
            }
            
            // Récupérer la promotion
            $cohort = Cohort::findOrFail($cohortId);
            
            // Récupérer les étudiants de cette promotion
            $students = [];
            
            // D'abord essayer via la relation users_cohorts
            $cohortStudents = $cohort->users()->get();
            
            if ($cohortStudents->isEmpty()) {
                // Méthode alternative - récupérer tous les étudiants
                $students = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
                    ->where('users_schools.role', 'student')
                    ->select('users.id', 'users.first_name', 'users.last_name')
                    ->get();
            } else {
                $students = $cohortStudents;
            }
            
            // Formatter les données pour éviter tout problème de sérialisation
            $formattedStudents = $students->map(function ($student) {
                return [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'last_name' => $student->last_name
                ];
            });
            
            return response()->json(['students' => $formattedStudents]);
            
        } catch (\Exception $e) {
            // Enregistrer l'erreur dans les logs pour le débogage
            \Log::error('Erreur dans getStudents: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Toujours renvoyer une réponse JSON valide, même en cas d'erreur
            return response()->json(['error' => 'Erreur serveur', 'students' => []], 500);
        }
    }
}
