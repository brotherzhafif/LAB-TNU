<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\LabBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms;

class DashboardLogAktivitasLab extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Log Aktivitas Lab';

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['admin', 'superadmin', 'monitor']);
    }

    protected function getTableQuery(): Builder
    {
        return LabBooking::query()->orderByDesc('created_at');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Pengguna')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lab.name')
                    ->label('Lab')
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
                Tables\Actions\Action::make('export-lab')
                    ->label('Export ke Excel')
                    ->url(route('export-log-aktivitas', ['type' => 'lab']))
                    ->openUrlInNewTab()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin', 'monitor'])),
            ]);
    }
}
