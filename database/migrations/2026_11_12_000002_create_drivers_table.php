<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_driver');
            $table->string('nomor_telepon');
            $table->string('nomor_sim');
            $table->enum('jenis_sim', ['SIM A', 'SIM B1', 'SIM B2 Umum'])->default('SIM A');
            $table->enum('status_driver', ['standby', 'bertugas'])->default('standby');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};

