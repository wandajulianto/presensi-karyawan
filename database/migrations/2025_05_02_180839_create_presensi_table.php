<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nik');
            $table->date('tanggal_presensi');
            $table->time('jam_masuk');
            $table->time('jam_keluar')->nullable();
            $table->string('foto_masuk');
            $table->string('foto_keluar')->nullable();
            $table->text('lokasi_masuk');
            $table->text('lokasi_keluar')->nullable();

            $table->index('nik');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
