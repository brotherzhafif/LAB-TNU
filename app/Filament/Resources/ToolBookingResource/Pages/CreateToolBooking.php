<?php

namespace App\Filament\Resources\ToolBookingResource\Pages;

use App\Filament\Resources\ToolBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateToolBooking extends CreateRecord
{
    protected static string $resource = ToolBookingResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()->hasRole('pengguna')) {
            $data['user_id'] = auth()->id();
        }

        return $data;
    }
    public function afterCreate()
    {
        $tool = $this->record->tool;
        $tool->available_quantity -= $this->record->jumlah;
        $tool->save();
    }
}
