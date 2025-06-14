<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToolBookingSelesaiController;
use App\Http\Controllers\LabBookingSelesaiController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/tool-booking/{record}/selesai-upload', [ToolBookingSelesaiController::class, 'upload'])->name('tool-booking.selesai-upload');
Route::post('/lab-booking/{record}/selesai-upload', [LabBookingSelesaiController::class, 'upload'])->name('lab-booking.selesai-upload');
