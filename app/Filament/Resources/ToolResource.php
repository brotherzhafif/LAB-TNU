<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolResource\Pages;
use App\Filament\Resources\ToolResource\RelationManagers;
use App\Models\Tool;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToolResource extends Resource
{
    protected static ?string $model = Tool::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?int $navigationSort = 20; // Tools setelah Labs

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama Alat')
                ->required(),

            Forms\Components\Select::make('lab_id')
                ->label('Laboratorium')
                ->relationship('lab', 'name')
                ->required(),

            Forms\Components\TextInput::make('total_quantity')
                ->label('Jumlah Total')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('available_quantity')
                ->label('Jumlah Tersedia')
                ->numeric()
                ->required(),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Alat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('lab.name')->label('Laboratorium')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('lab.location')->label('Lokasi Lab')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('total_quantity')->label('Total')->sortable(),
                Tables\Columns\TextColumn::make('available_quantity')->label('Tersedia')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lab_id')
                    ->label('Filter Lab')
                    ->relationship('lab', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin'])),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin'])),
                Tables\Actions\Action::make('book')
                    ->label('Book')
                    ->icon('heroicon-o-plus-circle')
                    ->url(fn($record) => route('filament.super-admin.resources.tool-bookings.create', [
                        'tool_id' => $record->id,
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
            'index' => Pages\ListTools::route('/'),
            'create' => Pages\CreateTool::route('/create'),
            'edit' => Pages\EditTool::route('/{record}/edit'),
        ];
    }
}
