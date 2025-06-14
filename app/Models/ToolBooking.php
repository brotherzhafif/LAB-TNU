<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolBooking extends Model
{
    use HasFactory;

    protected $table = 'tool_bookings';

    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'nit_nip',
        'tool_id',
        'lab_id',
        'course',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah',
        'jumlah_dikembalikan',
        'status',
        'bukti_selesai',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }

    // Method untuk validasi bentrok waktu booking alat
    public static function isConflict($toolId, $tanggal, $waktuMulai, $waktuSelesai, $excludeId = null)
    {
        $query = self::where('tool_id', $toolId)
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