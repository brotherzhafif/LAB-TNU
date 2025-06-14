<?php

namespace Database\Factories;

use App\Models\ToolBooking;
use App\Models\User;
use App\Models\Lab;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

class ToolBookingFactory extends Factory
{
    protected $model = ToolBooking::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('08:00', '16:00');
        $end = (clone $start)->modify('+1 hour');
        $jumlah = $this->faker->numberBetween(1, 5);

        return [
            'user_id' => User::factory(),
            'tool_id' => Tool::factory(),
            'lab_id' => Lab::factory(),
            'course' => $this->faker->words(2, true),
            'tanggal' => $this->faker->date(),
            'waktu_mulai' => $start->format('H:i:s'),
            'waktu_selesai' => $end->format('H:i:s'),
            'jumlah' => $jumlah,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'bukti_selesai' => null,
        ];
    }
}
