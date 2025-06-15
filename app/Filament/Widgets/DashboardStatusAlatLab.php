<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use App\Models\Lab;
use App\Models\Tool;
use App\Models\LabBooking;
use App\Models\ToolBooking;
use Illuminate\Support\Facades\Auth;

class DashboardStatusAlatLab extends BaseWidget
{
    protected static ?int $sort = 1;


    public static function canView(): bool
    {
        return Auth::user()->hasRole('pengguna');
    }

    public ?string $tanggal = null;
    public ?string $waktu = null;

    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|null
    {
        $tanggal = $this->tanggal ?? now()->toDateString();
        $waktu = $this->waktu ?? now()->format('H:i');

        $labQuery = Lab::query()
            ->selectRaw("'Lab' as tipe, name as nama, location as lokasi, id, null as tool_lab_id, (select CASE WHEN COUNT(*) > 0 THEN 'Dipinjam' ELSE 'Kosong' END from `lab_bookings` where `lab_id` = `labs`.`id` and `status` = 'approved' and `tanggal` = ? and `waktu_mulai` <= ? and `waktu_selesai` >= ?) as status", [$tanggal, $waktu, $waktu]);

        $toolQuery = Tool::query()
            ->selectRaw("'Alat' as tipe, name as nama, (select location from labs where labs.id = tools.lab_id limit 1) as lokasi, id, lab_id as tool_lab_id, (select CASE WHEN COUNT(*) > 0 THEN 'Dipinjam' ELSE 'Kosong' END from `tool_bookings` where `tool_id` = `tools`.`id` and `status` = 'approved' and `tanggal` = ? and `waktu_mulai` <= ? and `waktu_selesai` >= ?) as status", [$tanggal, $waktu, $waktu]);

        $union = $labQuery->unionAll($toolQuery);

        return Lab::query()->fromSub($union, 'alat_lab_union')->orderBy('nama');
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                Tables\Columns\TextColumn::make('tipe')->label('Tipe'),
                Tables\Columns\TextColumn::make('nama')->label('Nama'),
                Tables\Columns\TextColumn::make('lokasi')->label('Lokasi'),
                Tables\Columns\BadgeColumn::make('status')->label('Status')
                    ->colors([
                        'success' => 'Kosong',
                        'danger' => 'Dipinjam',
                    ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('tanggal')
                    ->label('Tanggal')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('tanggal')
                            ->default(now()->toDateString()),
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['tanggal'])) {
                            $this->tanggal = $data['tanggal'];
                        }
                    }),
                Tables\Filters\Filter::make('waktu')
                    ->label('Waktu (HH:MM)')
                    ->form([
                        \Filament\Forms\Components\TextInput::make('waktu')
                            ->default(now()->format('H:i')),
                    ])
                    ->query(function ($query, $data) {
                        if (!empty($data['waktu'])) {
                            $this->waktu = $data['waktu'];
                        }
                    }),
            ]);
    }
}
