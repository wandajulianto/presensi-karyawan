<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kantor', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kantor');
            $table->text('alamat');
            $table->decimal('latitude', 10, 8); // Koordinat latitude
            $table->decimal('longitude', 11, 8); // Koordinat longitude
            $table->integer('radius_meter')->default(20); // Radius dalam meter
            $table->string('kode_kantor')->unique(); // Kode unik kantor
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true); // Status aktif kantor
            $table->string('timezone')->default('Asia/Jakarta');
            $table->time('jam_masuk')->default('07:00:00');
            $table->time('jam_keluar')->default('17:00:00');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kantor');
    }
};
