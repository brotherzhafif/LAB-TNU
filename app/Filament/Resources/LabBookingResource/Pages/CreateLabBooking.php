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
            $data['nit_nip'] = auth()->user()->nit_nip; // autofill nit_nip
            $data['nama_pengguna'] = auth()->user()->name; // autofill nama_pengguna
        }
        // Prefill lab_id if present in query string
        if (request()->has('lab_id')) {
            $data['lab_id'] = request()->get('lab_id');
        }
        return $data;
    }

    protected function getFormSchema(): array
    {
        $labId = request()->get('lab_id');
        return [
            \Filament\Forms\Components\Select::make('lab_id')
                ->label('Laboratorium')
                ->relationship('lab', 'name')
                ->required()
                ->default($labId)
                ->disabled(fn() => filled($labId)),
            // ...existing code for other fields...
        ];
    }
}
