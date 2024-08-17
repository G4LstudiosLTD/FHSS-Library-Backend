<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\models\Student;
use App\models\User;
use App\models\School;
class StudentFactory extends Factory
{

    protected $model = Student::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'school_id' => School::factory(),
            'student_index' => $this->faker->unique()->regexify('[A-Za-z0-9]{4}'),
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'dob' => $this->faker->dateTimeBetween('-6 years', '-18 years')->format('Y-m-d'),
            'address' => $this->faker->address,
        ];
    }
}
