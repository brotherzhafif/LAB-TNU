<?php

namespace App\Filament\Resources\ToolBookingResource\Pages;

use App\Filament\Resources\ToolBookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListToolBookings extends ListRecords
{
    protected static string $resource = ToolBookingResource::class;

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

        // Only pengguna sees their own bookings
        if (auth()->user()->hasRole('pengguna')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }
}
