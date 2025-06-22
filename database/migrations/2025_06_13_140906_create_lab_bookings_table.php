<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('lab_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lab_id')->constrained()->onDelete('cascade');
            $table->string('course')->nullable();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->text('keperluan')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'returning', 'completed'])->default('pending');
            $table->string('bukti_selesai')->nullable(); // ubah dari json ke string
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_bookings');
    }
};
