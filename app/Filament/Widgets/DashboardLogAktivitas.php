<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\LabBooking;
use App\Models\ToolBooking;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\DateFilter;

class DashboardLogAktivitas extends BaseWidget
{
    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['admin', 'superadmin', 'monitor']);
    }

    protected function getTableQuery(): Builder
    {
        // $collation = 'utf8mb4_unicode_ci';

        $lab = \DB::table('lab_bookings')->selectRaw("
            'Lab' AS tipe,
            id,
            user_id,
            lab_id,
            NULL AS tool_id,
            tanggal,
            waktu_mulai,
            waktu_selesai,
            status,
            created_at
        ");

        $tool = \DB::table('tool_bookings')->selectRaw("
            'Alat' AS tipe,
            id,
            user_id,
            NULL AS lab_id,
            tool_id,
            tanggal,
            waktu_mulai,
            waktu_selesai,
            status,
            created_at
        ");

        // Union kedua tabel (Query\Builder)
        $union = $lab->unionAll($tool);

        // Wrap union as subquery for Eloquent\Builder
        $query = \App\Models\LabBooking::query()
            ->fromSub($union, 'logs')
            ->select('*');

        // Ambil filter dari request (bisa null)
        $filters = request('tableFilters') ?? [];
        $tanggal = $filters['tanggal'] ?? null;
        $periode = $filters['periode'] ?? null;

        // Hanya filter berdasarkan tanggal, JANGAN filter berdasarkan kolom 'periode'
        if ($tanggal && $periode) {
            if ($periode === 'hari') {
                $query->whereDate('tanggal', $tanggal);
            } elseif ($periode === 'bulan') {
                $query->whereMonth('tanggal', date('m', strtotime($tanggal)))
                    ->whereYear('tanggal', date('Y', strtotime($tanggal)));
            } elseif ($periode === 'tahun') {
                $query->whereYear('tanggal', date('Y', strtotime($tanggal)));
            }
        }

        return $query->orderByDesc('created_at');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('tipe')->label('Tipe'),
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Pengguna')
                    ->formatStateUsing(fn($state) => optional(\App\Models\User::find($state))->name),
                Tables\Columns\TextColumn::make('lab_id')
                    ->label('Lab')
                    ->formatStateUsing(fn($state) => $state ? optional(\App\Models\Lab::find($state))->name : '-'),
                Tables\Columns\TextColumn::make('tool_id')
                    ->label('Alat')
                    ->formatStateUsing(fn($state) => $state ? optional(\App\Models\Tool::find($state))->name : '-'),
                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('waktu_mulai')->label('Mulai'),
                Tables\Columns\TextColumn::make('waktu_selesai')->label('Selesai'),
                Tables\Columns\BadgeColumn::make('status')->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'approved',
                        'danger' => 'rejected',
                        'success' => 'completed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i'),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('tanggal'),
                        \Filament\Forms\Components\Select::make('periode')
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
                Tables\Actions\Action::make('export')
                    ->label('Export ke Excel')
                    ->url(route('export-log-aktivitas'))
                    ->openUrlInNewTab()
                    ->visible(fn() => auth()->user()->hasRole(['admin', 'superadmin', 'monitor'])),
            ]);
    }
}
