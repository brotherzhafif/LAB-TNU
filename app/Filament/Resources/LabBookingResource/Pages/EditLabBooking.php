<?php

namespace App\Filament\Resources\LabBookingResource\Pages;

use App\Filament\Resources\LabBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLabBooking extends EditRecord
{
    protected static string $resource = LabBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->hidden(fn($record) => auth()->user()?->hasRole('monitor') || ($record->status !== 'pending' && auth()->user()?->hasRole('pengguna'))),
        ];
    }
}
