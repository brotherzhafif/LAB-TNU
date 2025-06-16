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
            'bukti_selesai' => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_selesai')->store('bukti-alat', 's3');

        $booking->bukti_selesai = $path;
        $booking->status = 'completed';
        $booking->save();

        return redirect()->route('filament.super-admin.resources.tool-bookings.index')
            ->with('success', 'Peminjaman alat berhasil diselesaikan.');
    }
}
