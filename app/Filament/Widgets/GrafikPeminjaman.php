<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\LabBooking;
use App\Models\ToolBooking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GrafikPeminjaman extends ChartWidget
{
    protected static ?string $heading = 'Grafik Peminjaman Bulanan';
    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['admin', 'superadmin']);
    }
    protected function getData(): array
    {
        $labels = [];
        $labData = [];
        $toolData = [];

        // Ambil data 6 bulan terakhir
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');

            $labCount = LabBooking::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();

            $toolCount = ToolBooking::whereYear('tanggal', $month->year)
                ->whereMonth('tanggal', $month->month)
                ->count();

            $labData[] = $labCount;
            $toolData[] = $toolCount;
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
        return 'bar'; // Bisa diganti: 'bar', 'line', 'doughnut', etc.
    }
}
