<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Cek dulu apakah kolom sudah ada
        if (!Schema::hasColumn('karyawan', 'kode_departemen')) {
            Schema::table('karyawan', function (Blueprint $table) {
                $table->string('kode_departemen', 10)->after('foto')->nullable();
            });
        }

        // Tambahkan foreign key jika belum ada
        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreign('kode_departemen')
                ->references('kode_departemen')
                ->on('departemens')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['kode_departemen']);
            $table->dropColumn('kode_departemen');
        });
    }
};