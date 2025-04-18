<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('perpage', 5);

        $query = User::join('users_schools', 'users.id', '=', 'users_schools.user_id')
            ->where('users_schools.role', 'student')
            ->select('users.*');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.last_name', 'like', "%{$search}%")
                  ->orWhere('users.first_name', 'like', "%{$search}%");
            });
        }

        $students = $query->paginate($perPage)->appends($request->only('search', 'perpage'));

        return view('pages.students.index', [
            'students' => $students,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'birth_date' => 'nullable|date',
            'password' => 'required|min:6',
        ]);

        // Création de l'étudiant
        $student = new User();
        $student->last_name = $validated['last_name'];
        $student->first_name = $validated['first_name'];
        $student->email = $validated['email'];
        $student->birth_date = $validated['birth_date'] ?? null;
        $student->password = Hash::make($validated['password']);
        $student->save();

        // Associer l'étudiant à l'école avec le rôle "student"
        $school = auth()->user()->school();
        if ($school) {
            $student->belongsToMany(\App\Models\School::class, 'users_schools')
                ->withPivot('role')
                ->attach($school->id, ['role' => 'student']);
        }

        return redirect()->route('student.index')
            ->with('success', 'L\'étudiant a été ajouté avec succès');
    }
}
