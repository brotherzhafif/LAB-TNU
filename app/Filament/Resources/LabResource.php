<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LabResource\Pages;
use App\Filament\Resources\LabResource\RelationManagers;
use App\Models\Lab;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LabResource extends Resource
{
    protected static ?string $model = Lab::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?int $navigationSort = 10; // Labs setelah Dashboard

    public static function getGlobalSearchAttributes(): array
    {
        return ['name', 'location'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Lab')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('location')
                ->label('Lokasi/Lantai')
                ->maxLength(255),

        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Lab')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('location')->label('Lokasi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->label('Dibuat')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin'])),
                Tables\Actions\Action::make('book')
                    ->label('Book')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn($record) => route('filament.super-admin.resources.lab-bookings.create', [
                        'lab_id' => $record->id,
                    ]))
                    ->visible(fn() => auth()->user()->hasRole('pengguna')),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin'])),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLabs::route('/'),
            'create' => Pages\CreateLab::route('/create'),
            'edit' => Pages\EditLab::route('/{record}/edit'),
        ];
    }
}
