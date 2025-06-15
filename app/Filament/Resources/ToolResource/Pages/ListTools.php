<?php

namespace App\Filament\Resources\ToolResource\Pages;

use App\Filament\Resources\ToolResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTools extends ListRecords
{
    protected static string $resource = ToolResource::class;

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
            \Filament\Tables\Columns\TextColumn::make('name')->label('Nama Alat')->searchable()->sortable(),
            \Filament\Tables\Columns\TextColumn::make('lab.name')->label('Laboratorium')->searchable()->sortable(),
            \Filament\Tables\Columns\TextColumn::make('lab.location')->label('Lokasi')->searchable()->sortable(),
            \Filament\Tables\Columns\TextColumn::make('total_quantity')->label('Total')->sortable(),
            \Filament\Tables\Columns\TextColumn::make('available_quantity')->label('Tersedia')->sortable(),
            // ...tambahkan kolom lain jika perlu...
        ];
    }
}
