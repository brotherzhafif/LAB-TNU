<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lab>
 */
class LabFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company . ' Lab',
            'location' => $this->faker->randomElement(['Lantai 1', 'Lantai 2', 'Lantai 3']),
            'status' => $this->faker->randomElement(['aktif', 'nonaktif']),
        ];
    }
}
