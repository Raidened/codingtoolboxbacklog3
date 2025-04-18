<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class CohortController extends Controller
{
    /**
     * Display all available cohorts
     * @return Factory|View|Application|object
     */
    public function index() {
        $cohorts = Cohort::withCount('users')->get();
        return view('pages.cohorts.index', [
            'cohorts' => $cohorts
        ]);
    }


    /**
     * Display a specific cohort
     * @param Cohort $cohort
     * @return Application|Factory|object|View
     */
    public function show(Cohort $cohort) {
        // Récupérer tous les utilisateurs qui sont des étudiants
        $availableStudents = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'student')
            ->whereDoesntHave('cohorts', function($query) use ($cohort) {
                $query->where('cohort_id', $cohort->id);
            })
            ->select('users.*')
            ->distinct()
            ->get();

        return view('pages.cohorts.show', [
            'cohort' => $cohort,
            'availableStudents' => $availableStudents
        ]);
    }

    /**
     * Store a newly created cohort
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ], [
            'name.required' => 'Le nom de la promotion est obligatoire',
            'description.required' => 'La description est obligatoire',
            'start_date.required' => 'La date de début est obligatoire',
            'end_date.required' => 'La date de fin est obligatoire',
            'end_date.after' => 'La date de fin doit être postérieure à la date de début',
        ]);
        
        // Récupérer l'école associée à l'utilisateur actuel
        $school = auth()->user()->school();
        
        if (!$school) {
            return redirect()->back()->with('error', 'Vous devez être associé à une école pour créer une promotion');
        }
        
        // Création de la promotion
        $cohort = new Cohort();
        $cohort->school_id = $school->id;
        $cohort->name = $validated['name'];
        $cohort->description = $validated['description'];
        $cohort->start_date = $validated['start_date'];
        $cohort->end_date = $validated['end_date'];
        $cohort->save();
        
        return redirect()->route('cohort.index')
            ->with('success', 'La promotion a été créée avec succès');
    }
    
    /**
     * Add a student to a cohort
     * 
     * @param Request $request
     * @param Cohort $cohort
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addStudent(Request $request, Cohort $cohort)
    {
        // Validation des données
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        
        // Vérifier que l'utilisateur est un étudiant
        $user = User::findOrFail($validated['user_id']);
        $userRole = $user->school()->pivot->role ?? null;
        
        if ($userRole !== 'student') {
            return redirect()->back()->with('error', 'Seuls les étudiants peuvent être ajoutés à une promotion');
        }
        
        // Vérifier que l'étudiant n'est pas déjà dans la promotion
        if ($cohort->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('error', 'Cet étudiant est déjà dans la promotion');
        }
        
        // Ajouter l'étudiant à la promotion
        $cohort->users()->attach($user->id);
        
        return redirect()->route('cohort.show', $cohort->id)
            ->with('success', 'L\'étudiant a été ajouté à la promotion avec succès');
    }
    
    /**
     * Remove a student from a cohort
     * 
     * @param Cohort $cohort
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeStudent(Cohort $cohort, User $user)
    {
        // Vérifier que l'étudiant est bien dans la promotion
        if (!$cohort->users()->where('user_id', $user->id)->exists()) {
            return redirect()->route('cohort.show', $cohort->id)
                ->with('error', 'Cet étudiant n\'est pas dans cette promotion');
        }
        
        // Retirer l'étudiant de la promotion
        $cohort->users()->detach($user->id);
        
        return redirect()->route('cohort.show', $cohort->id)
            ->with('success', 'L\'étudiant a été retiré de la promotion avec succès');
    }
}
