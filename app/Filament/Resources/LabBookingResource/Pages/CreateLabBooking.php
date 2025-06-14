<?php

namespace App\Filament\Resources\LabBookingResource\Pages;

use App\Filament\Resources\LabBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreateLabBooking extends CreateRecord
{
    protected static string $resource = LabBookingResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('pengguna')) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
    
}
