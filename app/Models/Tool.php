<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lab;

class Tool extends Model
{
    use HasFactory;
    //
    protected $table = 'tools';
    protected $fillable = [
        'name',
        'lab_id',
        'total_quantity',
        'available_quantity',
    ];
    protected $casts = [
        'total_quantity' => 'integer',
        'available_quantity' => 'integer',
    ];

    // Relasi ke model Lab
    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }

}