<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    protected $model = Subject::class;

    public function definition()
    {
        return [
            'school_id' => \App\Models\School::factory(),
            'subject_name' => $this->faker->word,
            'subject_description' => $this->faker->sentence,
        ];
    }
}
