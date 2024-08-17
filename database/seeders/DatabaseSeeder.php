<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\Student;
use App\Models\User;
use App\Models\Subject_Routing;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 schools
        $schools = School::factory(2)->create();
    
        // Iterate over each school
        $schools->each(function ($school) {
            // Create 5 teachers for each school
            Teacher::factory(3)->create(['school_id' => $school->id]);
    
            // Create 5 grades for each school
            $grades = Grade::factory(3)->create(['school_id' => $school->id]);
    
            // Create 5 subjects for each school
            $subjects = Subject_Routing::factory(5)->create(['school_id' => $school->id]);
    
            // Create 5 users (teachers) for each school
            $teachers = User::factory(3)->create()->each(function ($user) use ($school) {
                $user->update(['role' => 'teacher']);
                Teacher::factory()->create(['user_id' => $user->id, 'school_id' => $school->id]);
            });
    
            // Create 5 students for each school
            $student = User::factory(3)->create()->each(function ($user) use ($school) {
                $user->update(['role' => 'student']);
                Student::factory()->create(['user_id' => $user->id, 'school_id' => $school->id]);
            });
        });
    }
}