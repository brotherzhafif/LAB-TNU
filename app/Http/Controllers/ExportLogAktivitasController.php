<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportLogAktivitasController extends Controller
{
    public function export(Request $request)
    {
        $lab = DB::table('lab_bookings')->selectRaw("
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

        $tool = DB::table('tool_bookings')->selectRaw("
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

        $union = $lab->unionAll($tool);
        $logs = DB::query()->fromSub($union, 'logs')->orderByDesc('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="log-aktivitas.csv"',
        ];

        $callback = function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tipe', 'Nama Pengguna', 'Lab', 'Alat', 'Tanggal', 'Mulai', 'Selesai', 'Status', 'Dibuat']);
            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->tipe,
                    optional(\App\Models\User::find($log->user_id))->name,
                    $log->lab_id ? optional(\App\Models\Lab::find($log->lab_id))->name : '-',
                    $log->tool_id ? optional(\App\Models\Tool::find($log->tool_id))->name : '-',
                    $log->tanggal,
                    $log->waktu_mulai,
                    $log->waktu_selesai,
                    $log->status,
                    $log->created_at,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
