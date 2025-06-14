<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ToolBookingResource\Pages;
use App\Filament\Resources\ToolBookingResource\RelationManagers;
use App\Models\ToolBooking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToolBookingResource extends Resource
{
    protected static ?string $model = ToolBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tool_id')
                ->label('Nama Alat')
                ->relationship('tool', 'name')
                ->required(),

            Forms\Components\Select::make('lab_id')
                ->label('Laboratorium')
                ->relationship('lab', 'name')
                ->required(),

            Forms\Components\TextInput::make('jumlah')
                ->label('Jumlah Dipinjam')
                ->numeric()
                ->required()
                ->minValue(1)
                ->maxValue(fn($get) => $get('tool_id') ? Tool::find($get('tool_id'))->available_quantity : null),

            Forms\Components\TextInput::make('course')
                ->label('Course / Mata Kuliah'),

            Forms\Components\DatePicker::make('tanggal')->label('Tanggal')->required(),
            Forms\Components\TimePicker::make('waktu_mulai')->label('Waktu Mulai')->required(),
            Forms\Components\TimePicker::make('waktu_selesai')->label('Waktu Selesai')->required(),

            Forms\Components\Textarea::make('keperluan')->label('Keperluan')->maxLength(255),

            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
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
                Tables\Columns\TextColumn::make('tool.name')->label('Alat'),
                Tables\Columns\TextColumn::make('lab.name')->label('Lab'),
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('waktu_mulai'),
                Tables\Columns\TextColumn::make('waktu_selesai'),
                Tables\Columns\TextColumn::make('jumlah')->label('Jumlah'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->url(fn($record) => route('filament.super-admin.resources.tool-bookings.selesai-peminjaman-alat', ['record' => $record->id]))
                    ->openUrlInNewTab()
                    ->visible(
                        fn($record) =>
                        auth()->user()->hasRole('pengguna') &&
                        $record->status === 'approved' &&
                        !$record->selesai
                    ),
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
            'index' => Pages\ListToolBookings::route('/'),
            'create' => Pages\CreateToolBooking::route('/create'),
            'edit' => Pages\EditToolBooking::route('/{record}/edit'),
            'selesai-peminjaman-alat' => Pages\SelesaiPeminjamanAlat::route('/{record}/selesai'),
        ];
    }
}
