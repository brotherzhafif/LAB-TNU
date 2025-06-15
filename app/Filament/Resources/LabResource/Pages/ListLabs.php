<?php

namespace App\Filament\Resources\LabResource\Pages;

use App\Filament\Resources\LabResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLabs extends ListRecords
{
    protected static string $resource = LabResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()?->hasRole(['pengguna', 'monitor'])) {
            return [];
        }
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableColumns(): array
    {
        return [
            \Filament\Tables\Columns\TextColumn::make('name')->label('Nama Lab')->searchable()->sortable(),
            \Filament\Tables\Columns\TextColumn::make('location')->label('Lokasi')->searchable()->sortable(),
            // ...tambahkan kolom lain jika perlu, pastikan pakai ->sortable()...
        ];
    }
}
