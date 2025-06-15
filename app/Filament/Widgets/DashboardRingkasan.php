<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use App\Models\LabBooking;
use App\Models\ToolBooking;

class DashboardRingkasan extends BaseWidget
{
    public ?string $filterTanggal = null;
    public ?string $filterPeriode = 'hari';

    public static function canView(): bool
    {
        return Auth::user()->hasRole(['admin', 'superadmin']);
    }

    protected function getFormSchema(): array
    {
        $user = Auth::user();
        if ($user->hasRole(['admin', 'superadmin'])) {
            return [
                \Filament\Forms\Components\Select::make('filterPeriode')
                    ->label('Periode')
                    ->options([
                        'hari' => 'Hari',
                        'bulan' => 'Bulan',
                        'tahun' => 'Tahun',
                    ])
                    ->default('hari'),
                \Filament\Forms\Components\DatePicker::make('filterTanggal')->label('Tanggal')->default(now()->toDateString()),
            ];
        }
        return [];
    }

    protected function getStats(): array
    {
        $user = Auth::user();

        if ($user->hasRole(['admin', 'superadmin'])) {
            $periode = $this->filterPeriode ?? 'hari';
            $tanggal = $this->filterTanggal ?? now()->toDateString();

            $labBookingQuery = LabBooking::query();
            $toolBookingQuery = ToolBooking::query();

            if ($periode === 'hari') {
                $labBookingQuery->whereDate('tanggal', $tanggal);
                $toolBookingQuery->whereDate('tanggal', $tanggal);
            } elseif ($periode === 'bulan') {
                $labBookingQuery->whereMonth('tanggal', date('m', strtotime($tanggal)))
                    ->whereYear('tanggal', date('Y', strtotime($tanggal)));
                $toolBookingQuery->whereMonth('tanggal', date('m', strtotime($tanggal)))
                    ->whereYear('tanggal', date('Y', strtotime($tanggal)));
            } elseif ($periode === 'tahun') {
                $labBookingQuery->whereYear('tanggal', date('Y', strtotime($tanggal)));
                $toolBookingQuery->whereYear('tanggal', date('Y', strtotime($tanggal)));
            }

            $totalLabBooking = $labBookingQuery->count();
            $totalToolBooking = $toolBookingQuery->count();

            return [
                Stat::make('Total Booking Lab', $totalLabBooking),
                Stat::make('Total Booking Alat', $totalToolBooking),
            ];
        }

        return [];
    }
}
