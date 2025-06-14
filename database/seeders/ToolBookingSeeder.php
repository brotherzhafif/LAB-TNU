<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ToolBooking;
use App\Models\User;
use App\Models\Lab;
use App\Models\Tool;

class ToolBookingSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $labs = Lab::all();

        foreach ($labs as $lab) {
            $tools = $lab->tools;
            foreach ($tools as $tool) {
                foreach ($users as $user) {
                    ToolBooking::factory()->count(2)->create([
                        'lab_id' => $lab->id,
                        'tool_id' => $tool->id,
                        'user_id' => $user->id,
                    ]);
                }
            }
        }
    }
}
