<?php

namespace App\Filament\Resources\ToolBookingResource\Pages;

use App\Filament\Resources\ToolBookingResource;
use Filament\Resources\Pages\Page;
use App\Models\ToolBooking;

class SelesaiPeminjamanAlat extends Page
{
    protected static string $resource = ToolBookingResource::class;
    protected static string $view = 'filament.resources.tool-booking-resource.pages.selesai-peminjaman-alat';

    public ToolBooking $booking;

    public function mount($record): void
    {
        $this->booking = ToolBooking::findOrFail($record);
        abort_unless(auth()->id() === $this->booking->user_id, 403);
    }
}
