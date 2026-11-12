<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lokasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_lokasi')->unique(); // HO-JKT, BO-KND, SITE-PML, dll
            $table->string('nama_lokasi');
            $table->enum('tipe_lokasi', ['kantor_pusat', 'kantor_cabang', 'tambang'])->default('tambang');
            $table->string('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lokasi');
    }
};

