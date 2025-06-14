<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabBooking;
use App\Models\User;
use App\Models\Lab;

class LabBookingSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $labs = Lab::all();

        foreach ($labs as $lab) {
            foreach ($users as $user) {
                LabBooking::factory()->count(2)->create([
                    'lab_id' => $lab->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
