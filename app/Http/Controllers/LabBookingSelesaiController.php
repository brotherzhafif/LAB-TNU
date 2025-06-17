<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabBooking;
use Illuminate\Support\Facades\Storage;

class LabBookingSelesaiController extends Controller
{
    public function upload(Request $request, $record)
    {
        $booking = LabBooking::findOrFail($record);
        abort_unless(auth()->id() === $booking->user_id, 403);

        $request->validate([
            'bukti_selesai' => 'required|image|max:2048',
        ]);

        $path = $request->file('bukti_selesai')->store('bukti-lab', env('FILESYSTEM_DISK', 'local'));

        $booking->bukti_selesai = $path;
        $booking->status = 'completed';
        $booking->save();

        return redirect()->route('filament.super-admin.resources.lab-bookings.index')
            ->with('success', 'Peminjaman berhasil ditandai selesai.');
    }
}
