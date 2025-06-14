<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tool;
use App\Models\Lab;

class ToolSeeder extends Seeder
{
    public function run()
    {
        $labs = Lab::all();
        foreach ($labs as $lab) {
            Tool::factory()->count(5)->create([
                'lab_id' => $lab->id,
            ]);
        }
    }
}
