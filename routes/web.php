<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolBookingSelesaiController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/tool-booking/{record}/selesai-upload', [ToolBookingSelesaiController::class, 'upload'])->name('tool-booking.selesai-upload');
