<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\UserSchool;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create the default user
        $admin = User::create([
            'last_name'     => 'Admin',
            'first_name'    => 'Admin',
            'email'         => 'admin@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $teacher = User::create([
            'last_name'     => 'Teacher',
            'first_name'    => 'Teacher',
            'email'         => 'teacher@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        $user = User::create([
            'last_name'     => 'Student',
            'first_name'    => 'Student',
            'email'         => 'student@codingfactory.com',
            'password'      => Hash::make('123456'),
        ]);

        // Create the default school
        $school = School::create([
            'user_id'   => $user->id,
            'name'      => 'Coding Factory',
        ]);

        // Create the admin role
        UserSchool::create([
            'user_id'   => $admin->id,
            'school_id' => $school->id,
            'role'      => 'admin'
        ]);

        // Create the teacher role
        UserSchool::create([
            'user_id'   => $teacher->id,
            'school_id' => $school->id,
            'role'      => 'teacher'
        ]);

        // Create the student role
        UserSchool::create([
            'user_id'   => $user->id,
            'school_id' => $school->id,
            'role'      => 'student'
        ]);

        // Create a cohort
        $cohort = \App\Models\Cohort::create([
            'school_id'   => $school->id,
            'name'        => 'Cohort 2025',
            'description' => 'Test cohort for 2025',
            'start_date'  => now()->subMonths(1),
            'end_date'    => now()->addMonths(11),
        ]);

        // Add 20 students
        $students = [];
        for ($i = 1; $i <= 20; $i++) {
            $student = User::create([
                'last_name'     => 'Student',
                'first_name'    => 'Student' . $i,
                'email'         => 'student' . $i . '@codingfactory.com',
                'password'      => Hash::make('123456'),
            ]);
            $students[] = $student;
            // Assign to school
            UserSchool::create([
                'user_id'   => $student->id,
                'school_id' => $school->id,
                'role'      => 'student'
            ]);
            // Assign to cohort
            \DB::table('users_cohorts')->insert([
                'user_id'    => $student->id,
                'cohort_id'  => $cohort->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Assign the original student to the cohort as well
        \DB::table('users_cohorts')->insert([
            'user_id'    => $user->id,
            'cohort_id'  => $cohort->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Add grades for each student
        foreach ($students as $student) {
            for ($g = 1; $g <= 3; $g++) {
                \App\Models\Grade::create([
                    'user_id'         => $student->id,
                    'cohort_id'       => $cohort->id,
                    'teacher_id'      => $teacher->id,
                    'title'           => 'Test ' . $g,
                    'value'           => rand(10, 20),
                    'evaluation_date' => now()->subDays(rand(1, 30)),
                    'description'     => 'skibidi test',
                ]);
            }
        }
    }
}