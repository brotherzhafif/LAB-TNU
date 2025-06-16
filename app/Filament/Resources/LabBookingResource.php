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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?int $navigationSort = 30; // Lab Bookings setelah Tools

    public static function form(Form $form): Form
    {
        $labId = request()->get('lab_id');
        return $form->schema([
            Forms\Components\Hidden::make('user_id')->default(auth()->id()),
            Forms\Components\TextInput::make('nama_pengguna')
                ->label('Nama Pengguna')
                ->required()
                ->default(fn() => auth()->user()?->name)
                ->disabled(
                    fn($record) => !auth()->user()->hasRole('pengguna') ||
                    ($record && $record->status !== 'pending')
                ),
            Forms\Components\TextInput::make('nit_nip')
                ->label('NIT/NIP')
                ->required()
                ->default(fn() => auth()->user()?->nit_nip)
                ->disabled(
                    fn($record) => !auth()->user()->hasRole('pengguna') ||
                    ($record && $record->status !== 'pending')
                ),
            Forms\Components\Select::make('lab_id')
                ->label('Laboratorium')
                ->relationship('lab', 'name')
                ->required()
                ->default($labId)
                ->disabled(
                    fn($record) => !auth()->user()->hasRole('pengguna') ||
                    ($record && $record->status !== 'pending')
                ),
            Forms\Components\TextInput::make('course')
                ->label('Course / Mata Kuliah')
                ->disabled(
                    fn($record) => !auth()->user()->hasRole('pengguna') ||
                    ($record && $record->status !== 'pending')
                ),

            Forms\Components\Grid::make(4)
                ->schema([
                    Forms\Components\DatePicker::make('tanggal')
                        ->label('Tanggal')
                        ->required()
                        ->columnSpan(2)
                        ->disabled(
                            fn($record) => !auth()->user()->hasRole('pengguna') ||
                            ($record && $record->status !== 'pending')
                        ),
                    Forms\Components\TimePicker::make('waktu_mulai')
                        ->label('Waktu Mulai')
                        ->withoutSeconds()
                        ->required()
                        ->columnSpan(1)
                        ->disabled(
                            fn($record) => !auth()->user()->hasRole('pengguna') ||
                            ($record && $record->status !== 'pending')
                        ),
                    Forms\Components\TimePicker::make('waktu_selesai')
                        ->label('Waktu Selesai')
                        ->withoutSeconds()
                        ->required()
                        ->columnSpan(1)
                        ->disabled(
                            fn($record) => !auth()->user()->hasRole('pengguna') ||
                            ($record && $record->status !== 'pending')
                        ),
                ])
                ->afterStateHydrated(function ($component, $state) {
                    // ...existing code...
                }),
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Hidden::make('conflict_check')->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                        $labId = $get('lab_id');
                        $tanggal = $get('tanggal');
                        $mulai = $get('waktu_mulai');
                        $selesai = $get('waktu_selesai');
                        $excludeId = $record ? $record->id : null;
                        if ($labId && $tanggal && $mulai && $selesai) {
                            if (\App\Models\LabBooking::isConflict($labId, $tanggal, $mulai, $selesai, $excludeId)) {
                                throw new \Exception('Waktu peminjaman lab bentrok dengan booking lain.');
                            }
                        }
                    }),
                ]),

            Forms\Components\Select::make('status')
                ->options(function ($get, $record) {
                    $options = [
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ];
                    // Jika status completed, tampilkan juga completed
                    if (($record && $record->status === 'completed') || $get('status') === 'completed') {
                        $options['completed'] = 'Completed';
                    }
                    return $options;
                })
                ->default('pending')
                ->hidden(
                    fn($record) =>
                    auth()->user()->hasRole('pengguna')
                )
                ->disabled(fn($record) => $record && $record->status === 'completed'),
        ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengguna')->label('Nama Pengguna')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nit_nip')->label('NIT/NIP')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('lab.name')->label('Lab')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->searchable()->sortable(),
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
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'completed' => 'Completed',
                ]),
            ])
            ->actions([
                // Hanya admin/operator yang bisa edit/delete/selesai
                Tables\Actions\EditAction::make()
                    ->visible(fn() => !auth()->user()->hasRole('pengguna')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn() => !auth()->user()->hasRole('pengguna')),
                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->color('success')
                    ->icon('heroicon-m-check-circle')
                    ->url(fn($record) => route('filament.super-admin.resources.lab-bookings.selesai-peminjaman-lab', ['record' => $record->id]))
                    ->visible(
                        fn($record) =>
                        auth()->user()->hasRole('pengguna') &&
                        $record->status === 'approved'
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
            'index' => Pages\ListLabBookings::route('/'),
            'create' => Pages\CreateLabBooking::route('/create'),
            'edit' => Pages\EditLabBooking::route('/{record}/edit'),
            'selesai-peminjaman-lab' => Pages\SelesaiPeminjamanLab::route('/{record}/selesai'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest('created_at');
    }
}
