<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_pemesanan')->unique(); // BKG-202611-0001
            $table->foreignId('admin_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_pemesan');
            $table->string('divisi_pemesan');
            $table->text('keperluan');
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->foreignId('driver_id')->constrained('drivers')->cascadeOnDelete();
            $table->foreignId('lokasi_tujuan_id')->constrained('lokasi')->cascadeOnDelete();
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai');
            $table->enum('status_pemesanan', [
                'menunggu_level_1',
                'menunggu_level_2',
                'disetujui',
                'ditolak',
                'selesai'
            ])->default('menunggu_level_1');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemesanan');
    }
};

