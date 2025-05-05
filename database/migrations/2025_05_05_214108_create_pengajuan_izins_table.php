<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_izins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nik');
            $table->date('tanggal_izin');
            $table->enum('status', ['i', 's']); // i: Izin, s: Sakit
            $table->text('keterangan');
            $table->unsignedTinyInteger('status_approved')->default(0); // 0: pending, 1: disetujui, 2: ditolak
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izins');
    }
};
