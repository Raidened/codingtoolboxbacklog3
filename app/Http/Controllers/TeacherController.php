<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perpage', 10);

        $query = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'teacher')
            ->select('users.*');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.last_name', 'like', "%{$search}%")
                  ->orWhere('users.first_name', 'like', "%{$search}%");
            });
        }

        $teachers = $query->paginate($perPage)
            ->appends($request->only('search', 'perpage'));

        return view('pages.teachers.index', ['teachers' => $teachers]);
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // Création de l'enseignant
        $teacher = new User();
        $teacher->last_name = $validated['last_name'];
        $teacher->first_name = $validated['first_name'];
        $teacher->email = $validated['email'];
        $teacher->password = Hash::make($validated['password']);
        $teacher->save();

        // Associer l'enseignant à l'école avec le rôle "teacher"
        $school = auth()->user()->school();
        if ($school) {
            $teacher->belongsToMany(\App\Models\School::class, 'users_schools')
                ->withPivot('role')
                ->attach($school->id, ['role' => 'teacher']);
        }

        return redirect()->route('teacher.index')
            ->with('success', 'L\'enseignant a été ajouté avec succès');
    }
}