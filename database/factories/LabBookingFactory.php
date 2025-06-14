<?php

namespace Database\Factories;

use App\Models\LabBooking;
use App\Models\User;
use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\Factory;

class LabBookingFactory extends Factory
{
    protected $model = LabBooking::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('08:00', '16:00');
        $end = (clone $start)->modify('+2 hours');
        return [
            'user_id' => User::factory(),
            'lab_id' => Lab::factory(),
            'course' => $this->faker->words(2, true),
            'tanggal' => $this->faker->date(),
            'waktu_mulai' => $start->format('H:i:s'),
            'waktu_selesai' => $end->format('H:i:s'),
            'keperluan' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'bukti_selesai' => null,
        ];
    }
}
