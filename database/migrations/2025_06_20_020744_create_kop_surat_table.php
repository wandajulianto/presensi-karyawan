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
        Schema::create('kop_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_instansi');
            $table->text('alamat_instansi');
            $table->string('telepon_instansi')->nullable();
            $table->string('email_instansi')->nullable();
            $table->string('website_instansi')->nullable();
            $table->string('logo_instansi')->nullable();
            $table->string('nama_pimpinan');
            $table->string('jabatan_pimpinan');
            $table->string('nip_pimpinan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kop_surat');
    }
};
