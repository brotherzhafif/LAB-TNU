<?php

namespace App\Filament\Resources\LabBookingResource\Pages;

use App\Filament\Resources\LabBookingResource;
use App\Models\LabBooking;
use Filament\Resources\Pages\Page;

class SelesaiPeminjamanLab extends Page
{
    protected static string $resource = LabBookingResource::class;
    protected static string $view = 'filament.resources.lab-booking-resource.pages.selesai-peminjaman-lab';

    public LabBooking $booking;

    public function mount($record): void
    {
        $this->booking = LabBooking::findOrFail($record);
        abort_unless(auth()->id() === $this->booking->user_id, 403);
    }
}