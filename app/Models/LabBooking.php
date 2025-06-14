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

}
