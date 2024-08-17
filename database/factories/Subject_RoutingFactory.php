<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Subject_RoutingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_id' => \App\Models\School::factory(),
            'grade_id' => \App\Models\Grade::factory(),
            'subject_id' => \App\Models\Subject::factory(),
            'teacher_id' => \App\Models\Teacher::factory(),

        ];
    }
}
