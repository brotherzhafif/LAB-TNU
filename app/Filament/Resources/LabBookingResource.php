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
                ]),

            Forms\Components\ViewField::make('bukti_selesai_preview')
                ->label('Bukti Selesai')
                ->view('filament.components.bukti-selesai-preview')
                ->hidden(fn($record) => !$record),

            Forms\Components\Select::make('status')
                ->options(function ($get, $record) {
                    $options = [
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ];
                    // Jika status completed, tampilkan juga completed
                    if (
                        ($record && in_array($record->status, ['completed'])) ||
                        in_array($get('status'), ['completed'])
                    ) {
                        $options['completed'] = 'Completed';
                    }
                    // Jika status returning, tampilkan juga returning
                    if (
                        ($record && in_array($record->status, ['returning'])) ||
                        in_array($get('status'), ['returning'])
                    ) {
                        $options['returning'] = 'Returning';
                    }
                    return $options;
                })
                ->default('pending')
                ->disabled(fn($record) => $record && $record->status === 'completed' ||
                    auth()->user()->hasRole('pengguna')),
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
                        'primary' => 'returning',
                        'success' => 'completed',
                    ])
                    ->disabled(
                        fn($record) => $record && $record->status === 'completed' ||
                        auth()->user()?->hasRole(['pengguna', 'monitor'])
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'returning' => 'Returning',
                    'completed' => 'Completed',
                ]),
            ])
            ->actions([
                // Hanya admin/operator yang bisa edit/delete/selesai
                Tables\Actions\EditAction::make()
                    ->visible(
                        fn($record) =>
                        !auth()->user()->hasRole('monitor') &&
                        (auth()->user()->hasRole(['admin', 'superadmin']) ||
                            (auth()->user()->hasRole('pengguna') && $record->status === 'pending'))
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn($record) =>
                        !auth()->user()->hasRole('monitor') &&
                        (auth()->user()->hasRole(['admin', 'superadmin']) ||
                            (auth()->user()->hasRole('pengguna') && $record->status === 'pending'))
                    ),
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
                // Add new action for admins to complete returning bookings
                Tables\Actions\Action::make('complete')
                    ->label('Verifikasi Selesai')
                    ->color('success')
                    ->icon('heroicon-m-check-badge')
                    ->action(function ($record) {
                        $record->status = 'completed';
                        $record->save();
                    })
                    ->visible(
                        fn($record) =>
                        auth()->user()->hasRole(['admin', 'superadmin']) &&
                        $record->status === 'returning'
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
