<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\LabBooking;
use App\Models\ToolBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GrafikPeminjaman extends ChartWidget
{
    protected static ?string $heading = 'Grafik Peminjaman';
    protected static ?int $sort = 2;

    public ?string $filterPeriode = 'bulan';
    public ?string $filterTanggal = null;
    public ?string $filterTipeGrafik = 'bar'; // Tambah properti tipe grafik

    protected static array $periodOptions = [
        'hari' => 'Hari',
        'bulan' => 'Bulan',
        'tahun' => 'Tahun',
    ];

    protected static array $chartTypeOptions = [
        'bar' => 'Bar',
        'line' => 'Line',
        'pie' => 'Pie',
    ];

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['admin', 'superadmin']);
    }

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\Select::make('filterPeriode')
                ->label('Periode')
                ->options(self::$periodOptions)
                ->default('bulan')
                ->reactive(),
            \Filament\Forms\Components\DatePicker::make('filterTanggal')
                ->label('Tanggal')
                ->default(now()->toDateString())
                ->reactive(),
            \Filament\Forms\Components\Select::make('filterTipeGrafik')
                ->label('Tipe Grafik')
                ->options(self::$chartTypeOptions)
                ->default('bar')
                ->reactive(),
        ];
    }

    protected function getData(): array
    {
        $periode = $this->filterPeriode ?? 'bulan';
        $tanggal = $this->filterTanggal ?? now()->toDateString();

        $labels = [];
        $labData = [];
        $toolData = [];

        if ($periode === 'hari') {
            // 7 hari terakhir
            for ($i = 6; $i >= 0; $i--) {
                $date = \Carbon\Carbon::parse($tanggal)->subDays($i);
                $labels[] = $date->format('d M Y');

                $labCount = \App\Models\LabBooking::whereDate('tanggal', $date)->count();
                $toolCount = \App\Models\ToolBooking::whereDate('tanggal', $date)->count();

                $labData[] = $labCount;
                $toolData[] = $toolCount;
            }
        } elseif ($periode === 'bulan') {
            // 6 bulan terakhir
            for ($i = 5; $i >= 0; $i--) {
                $month = \Carbon\Carbon::parse($tanggal)->subMonths($i);
                $labels[] = $month->format('M Y');

                $labCount = \App\Models\LabBooking::whereYear('tanggal', $month->year)
                    ->whereMonth('tanggal', $month->month)
                    ->count();
                $toolCount = \App\Models\ToolBooking::whereYear('tanggal', $month->year)
                    ->whereMonth('tanggal', $month->month)
                    ->count();

                $labData[] = $labCount;
                $toolData[] = $toolCount;
            }
        } elseif ($periode === 'tahun') {
            // 6 tahun terakhir
            for ($i = 5; $i >= 0; $i--) {
                $year = \Carbon\Carbon::parse($tanggal)->subYears($i)->year;
                $labels[] = (string) $year;

                $labCount = \App\Models\LabBooking::whereYear('tanggal', $year)->count();
                $toolCount = \App\Models\ToolBooking::whereYear('tanggal', $year)->count();

                $labData[] = $labCount;
                $toolData[] = $toolCount;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Lab',
                    'data' => $labData,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgba(59, 130, 246, 1)',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Alat',
                    'data' => $toolData,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgba(16, 185, 129, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return $this->filterTipeGrafik ?? 'bar';
    }
}
