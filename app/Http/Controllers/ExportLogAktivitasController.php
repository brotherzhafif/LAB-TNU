<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\LogAktivitasExport;
use Maatwebsite\Excel\Facades\Excel;
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

        return Excel::download(new LogAktivitasExport($logs), 'log-aktivitas.xlsx');
    }
}
