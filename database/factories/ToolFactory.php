<?php

namespace Database\Factories;

use App\Models\Tool;
use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToolFactory extends Factory
{
    protected $model = Tool::class;

    public function definition(): array
    {
        $total = $this->faker->numberBetween(1, 10);
        return [
            'name' => $this->faker->word() . ' Tool',
            'lab_id' => Lab::factory(),
            'total_quantity' => $total,
            'available_quantity' => $total,
        ];
    }
}
