<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('tool_bookings', function (Blueprint $table) {
            $table->string('nama_pengguna')->nullable()->after('user_id');
            $table->string('nit_nip')->nullable()->after('nama_pengguna');
        });

        Schema::table('lab_bookings', function (Blueprint $table) {
            $table->string('nama_pengguna')->nullable()->after('user_id');
            $table->string('nit_nip')->nullable()->after('nama_pengguna');
        });
    }

    public function down()
    {
        Schema::table('tool_bookings', function (Blueprint $table) {
            $table->dropColumn(['nama_pengguna', 'nit_nip']);
        });

        Schema::table('lab_bookings', function (Blueprint $table) {
            $table->dropColumn(['nama_pengguna', 'nit_nip']);
        });
    }
};
