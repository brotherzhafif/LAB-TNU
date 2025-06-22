<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\ToolBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;

class DashboardLogAktivitasAlat extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Log Aktivitas Alat';

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['admin', 'superadmin', 'monitor']);
    }

    protected function getTableQuery(): Builder
    {
        return ToolBooking::query()->orderByDesc('created_at');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tool.name')
                    ->label('Alat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('waktu_mulai')->label('Mulai'),
                Tables\Columns\TextColumn::make('waktu_selesai')->label('Selesai'),
                Tables\Columns\BadgeColumn::make('status')->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'approved',
                        'danger' => 'rejected',
                        'primary' => 'returning',
                        'success' => 'completed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('tanggal'),
                        Forms\Components\Select::make('periode')
                            ->options([
                                'hari' => 'Hari',
                                'bulan' => 'Bulan',
                                'tahun' => 'Tahun',
                            ])
                            ->default('hari')
                    ])
                    ->query(function ($query, $data) {
                        if (!$data['tanggal'] || !$data['periode'])
                            return;

                        if ($data['periode'] === 'hari') {
                            $query->whereDate('tanggal', $data['tanggal']);
                        } elseif ($data['periode'] === 'bulan') {
                            $query->whereMonth('tanggal', date('m', strtotime($data['tanggal'])))
                                ->whereYear('tanggal', date('Y', strtotime($data['tanggal'])));
                        } elseif ($data['periode'] === 'tahun') {
                            $query->whereYear('tanggal', date('Y', strtotime($data['tanggal'])));
                        }
                    })
            ])
            ->searchable()
            ->headerActions([
                Tables\Actions\Action::make('export-tool')
                    ->label('Export ke Excel')
                    ->url(route('export-log-aktivitas', ['type' => 'tool']))
                    ->openUrlInNewTab()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin', 'monitor'])),
            ]);
    }
}
