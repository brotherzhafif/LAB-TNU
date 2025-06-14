<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToolBooking;
use Illuminate\Support\Facades\Storage;

class ToolBookingSelesaiController extends Controller
{
    public function upload(Request $request, $record)
    {
        $booking = ToolBooking::findOrFail($record);
        abort_unless(auth()->id() === $booking->user_id, 403);

        $request->validate([
            'jumlah_dikembalikan' => 'required|integer|min:0|max:' . $booking->jumlah,
            'bukti_selesai' => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_selesai')->store('bukti-alat', 'public');

        // Simpan file bukti_selesai saja, tidak perlu update kolom jumlah_dikembalikan
        $booking->bukti_selesai = $path;
        $booking->status = 'completed';
        $booking->save();

        // Update stok alat
        $tool = $booking->tool;
        $tool->available_quantity += (int) $request->input('jumlah_dikembalikan');
        $tool->save();

        return redirect()->route('filament.super-admin.resources.tool-bookings.index')
            ->with('success', 'Peminjaman alat berhasil diselesaikan.');
    }
}
