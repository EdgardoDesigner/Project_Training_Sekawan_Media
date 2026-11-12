<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('aksi'); // BUAT_PEMESANAN, SETUJUI_LEVEL_1, SETUJUI_LEVEL_2, TOLAK_PEMESANAN, dll
            $table->text('deskripsi');
            $table->string('alamat_ip', 45)->nullable();
            $table->text('perangkat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};

