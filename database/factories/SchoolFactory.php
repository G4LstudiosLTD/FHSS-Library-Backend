<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    protected $model = School::class;

    public function definition()
    {

        // Ensure the directory exists
        $dir = storage_path('app/public/images');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Generate a local image file
        $imagePath = $this->faker->image($dir, 100, 100, 'education', false);

        // Get the full path to the image file
        $fullImagePath = $dir . '/' . $imagePath;

        // Initialize $imageData
        $imageData = null;

        // Check if the image file was created and is not an empty string
        if ($imagePath && file_exists($fullImagePath)) {
            // Read the image file and convert it to base64
            $imageData = base64_encode(file_get_contents($fullImagePath));

            // Optionally delete the local image file
            unlink($fullImagePath);
        }


        return [
            'school_name' => $this->faker->company,
            'address' => $this->faker->address,
            'state' => $this->faker->state,
            'city' => $this->faker->city,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'short_code' => $this->faker->unique()->regexify('[A-Z]{3}'),
            'logo' => $imageData, // You can adjust this according to your needs
        ];
    }
}