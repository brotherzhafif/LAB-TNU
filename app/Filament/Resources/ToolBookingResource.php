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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ToolBookingResource extends Resource
{
    protected static ?string $model = ToolBooking::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 40; // Tool Bookings setelah Lab Bookings

    public static function form(Form $form): Form
    {
        $toolId = request()->get('tool_id');
        return $form->schema([
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

            Forms\Components\Select::make('tool_id')
                ->label('Nama Alat')
                ->relationship('tool', 'name')
                ->required()
                ->default($toolId)
                ->disabled(fn() => filled($toolId) || !auth()->user()->hasRole('pengguna') || (request()->route('record') && request()->route('record')->status !== 'pending'))
                ->reactive(),

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

            Forms\Components\Select::make('jumlah')
                ->label('Jumlah Dipinjam')
                ->options(function ($get, $record) {
                    $toolId = $get('tool_id') ?? ($record ? $record->tool_id : null);
                    if (!$toolId)
                        return [];
                    $max = \App\Models\Tool::find($toolId)?->available_quantity ?? 0;

                    $current = $record ? $record->jumlah : null;
                    $range = $max > 0 ? range(1, $max) : [];
                    if ($current && !in_array($current, $range)) {
                        $range[] = $current;
                        sort($range);
                    }
                    return collect($range)->mapWithKeys(fn($v) => [$v => $v])->toArray();
                })
                ->required()
                ->reactive()
                ->default(fn($record) => $record ? $record->jumlah : null)
                ->disabled(fn($record) => !auth()->user()->hasRole('pengguna')),

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

                    if (($record && $record->status === 'completed') || $get('status') === 'completed') {
                        $options['completed'] = 'Completed';
                    }
                    return $options;
                })
                ->default('pending')
                ->hidden(fn() => auth()->user()->hasRole('pengguna'))
                ->disabled(fn($record) => $record && $record->status === 'completed'),

            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Hidden::make('conflict_check')->afterStateUpdated(function ($state, callable $set, callable $get, $record) {
                        $toolId = $get('tool_id');
                        $tanggal = $get('tanggal');
                        $mulai = $get('waktu_mulai');
                        $selesai = $get('waktu_selesai');
                        $excludeId = $record ? $record->id : null;
                        if ($toolId && $tanggal && $mulai && $selesai) {
                            if (\App\Models\ToolBooking::isConflict($toolId, $tanggal, $mulai, $selesai, $excludeId)) {
                                throw \Filament\Forms\Exception\ComponentException::make('Waktu peminjaman alat bentrok dengan booking lain.');
                            }
                        }
                    }),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pengguna')->label('Nama Pengguna')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nit_nip')->label('NIT/NIP')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tool.name')->label('Alat')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date('d M Y')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('waktu_mulai'),
                Tables\Columns\TextColumn::make('waktu_selesai'),
                Tables\Columns\TextColumn::make('jumlah')->label('Jumlah'),
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
                Tables\Actions\EditAction::make()
                    ->visible(
                        fn($record) =>
                        $record->status === 'pending'
                        && auth()->user()->hasRole('pengguna')
                    ),
                Tables\Actions\DeleteAction::make()
                    ->visible(
                        fn($record) =>
                        $record->status === 'pending'
                        && auth()->user()->hasRole('pengguna')
                    ),
                Tables\Actions\Action::make('selesai')
                    ->label('Selesai')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->url(fn($record) => route('filament.super-admin.resources.tool-bookings.selesai-peminjaman-alat', ['record' => $record->id]))
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
            'index' => Pages\ListToolBookings::route('/'),
            'create' => Pages\CreateToolBooking::route('/create'),
            'edit' => Pages\EditToolBooking::route('/{record}/edit'),
            'selesai-peminjaman-alat' => Pages\SelesaiPeminjamanAlat::route('/{record}/selesai'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest('created_at');
    }
}
