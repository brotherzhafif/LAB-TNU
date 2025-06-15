<?php

namespace App\Filament\Resources\LabBookingResource\Pages;

use App\Filament\Resources\LabBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLabBookings extends ListRecords
{
    protected static string $resource = LabBookingResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()->hasRole('pengguna')) {
            return [
                Actions\CreateAction::make(),
            ];
        }
        return [];
    }

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery();

        if (auth()->user()->hasRole('pengguna')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
