<?php

namespace App\Filament\Resources\ToolBookingResource\Pages;

use App\Filament\Resources\ToolBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditToolBooking extends EditRecord
{
    protected static string $resource = ToolBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
            ->hidden(fn($record) => auth()->user()?->hasRole('monitor') || ($record->status !== 'pending' && auth()->user()?->hasRole('pengguna')))
        ];
    }

}
