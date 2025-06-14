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
        'tool_id',
        'lab_id',
        'course',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'jumlah',
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

}