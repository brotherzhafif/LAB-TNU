<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LabBookingResource\Pages;
use App\Filament\Resources\LabBookingResource\RelationManagers;
use App\Models\LabBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Hidden;

class LabBookingResource extends Resource
{
    protected static ?string $model = LabBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Hidden::make('user_id')->default(auth()->id()),
            Forms\Components\Select::make('lab_id')
                ->label('Laboratorium')
                ->relationship('lab', 'name')
                ->required(),

            Forms\Components\TextInput::make('course')
                ->label('Course / Mata Kuliah'),

            Forms\Components\DatePicker::make('tanggal')
                ->label('Tanggal')
                ->required(),

            Forms\Components\TimePicker::make('waktu_mulai')
                ->label('Waktu Mulai')
                ->required(),

            Forms\Components\TimePicker::make('waktu_selesai')
                ->label('Waktu Selesai')
                ->required(),

            Forms\Components\Textarea::make('keperluan')
                ->label('Keperluan'),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'completed' => 'Completed',
                ])
                ->default('pending')
                ->disabled(fn() => auth()->user()->hasRole('pengguna')),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Pengguna'),
                Tables\Columns\TextColumn::make('lab.name')->label('Lab'),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('waktu_mulai'),
                Tables\Columns\TextColumn::make('waktu_selesai'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'approved',
                        'danger' => 'rejected',
                        'success' => 'completed',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->status === 'pending'),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn($record) => $record->status === 'pending'),
                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-m-check-circle')
                    ->url(fn($record) => route('filament.super-admin.resources.lab-bookings.selesai-peminjaman-lab', ['record' => $record->id]))
                    ->visible(fn($record) => $record->status === 'approved')
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
            'index' => Pages\ListLabBookings::route('/'),
            'create' => Pages\CreateLabBooking::route('/create'),
            'edit' => Pages\EditLabBooking::route('/{record}/edit'),
            'selesai-peminjaman-lab' => Pages\SelesaiPeminjamanLab::route('/{record}/selesai'),
        ];
    }


}
