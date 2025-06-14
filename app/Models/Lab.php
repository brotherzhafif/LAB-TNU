<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; // tambahkan ini
use App\Models\Tool;

class Lab extends Model
{
    use HasFactory; // tambahkan ini

    // Nama tabel (opsional, jika nama model dan tabel sudah sesuai konvensi Laravel, bisa dihapus)
    protected $table = 'labs';

    // Field yang bisa diisi massal
    protected $fillable = [
        'name',
        'location',
        'status',
    ];

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }

    //
}