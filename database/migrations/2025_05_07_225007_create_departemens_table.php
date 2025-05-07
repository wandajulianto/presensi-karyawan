<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('departemens', function (Blueprint $table) {
            $table->string('kode_departemen', 10)->primary();
            $table->string('nama_departemen', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('departemens');
    }
};