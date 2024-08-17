<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\School;
use App\Models\User;

class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'school_id' => School::factory(),
            'user_id' => User::factory(),
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email' => $this->faker->email,
            'number' => $this->faker->phoneNumber,
            'address' => $this->faker->address, 
        ];
    }
}
