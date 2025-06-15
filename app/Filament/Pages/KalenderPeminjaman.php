<?php

namespace App\Filament\Pages;

use App\Models\LabBooking;
use App\Models\ToolBooking;
use Filament\Pages\Page;

class KalenderPeminjaman extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 60; // Kalender paling bawah
    protected static string $view = 'filament.pages.kalender-peminjaman';
    protected static ?string $title = 'Kalender Pemakaian Lab & Alat';

    public array $events = [];

    public function mount(): void
    {
        // Event pemakaian lab
        $labEvents = LabBooking::with('lab', 'user')
            ->where('status', 'approved')
            ->get()
            ->map(function ($booking) {
                return [
                    'title' => '[Lab] ' . $booking->lab->name . ' - ' . $booking->user->name,
                    'start' => $booking->tanggal . 'T' . $booking->waktu_mulai,
                    'end' => $booking->tanggal . 'T' . $booking->waktu_selesai,
                    'color' => '#1d4ed8', // biru
                ];
            })
            ->toArray(); // Convert to array

        // Event pemakaian alat
        $toolEvents = ToolBooking::with('tool', 'user')
            ->where('status', 'approved')
            ->get()
            ->map(function ($booking) {
                return [
                    'title' => '[Alat] ' . $booking->tool->name . ' - ' . $booking->user->name,
                    'start' => $booking->tanggal . 'T' . $booking->waktu_mulai,
                    'end' => $booking->tanggal . 'T' . $booking->waktu_selesai,
                    'color' => '#059669', // hijau
                ];
            })
            ->toArray(); // Convert to array

        // Gabungkan jadi satu array
        $this->events = array_merge($labEvents, $toolEvents);
    }
}
