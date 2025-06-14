<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Lab;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Import HasFactory trait

class LabBooking extends Model
{
    use HasFactory;
    protected $table = 'lab_bookings';

    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'nit_nip',
        'lab_id',
        'course',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'keperluan',
        'status',
        'bukti_selesai',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public static function isConflict($labId, $tanggal, $waktuMulai, $waktuSelesai, $excludeId = null)
    {
        $query = self::where('lab_id', $labId)
            ->where('tanggal', $tanggal)
            ->where(function ($q) use ($waktuMulai, $waktuSelesai) {
                $q->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])
                    ->orWhereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])
                    ->orWhere(function ($q2) use ($waktuMulai, $waktuSelesai) {
                        $q2->where('waktu_mulai', '<=', $waktuMulai)
                            ->where('waktu_selesai', '>=', $waktuSelesai);
                    });
            });
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        return $query->whereIn('status', ['pending', 'approved'])->exists();
    }
}
