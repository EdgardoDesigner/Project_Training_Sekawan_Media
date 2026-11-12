<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_polisi')->unique(); // Plat nomor, misal DT 8821 XX
            $table->string('nomor_lambung')->unique(); // Nomor unit tambang, misal LV-01, DT-05
            $table->string('merk_model'); // misal Toyota Hilux Double Cabin 4x4
            $table->enum('jenis_kendaraan', ['angkutan_orang', 'angkutan_barang'])->default('angkutan_orang');
            $table->enum('kepemilikan', ['milik_perusahaan', 'sewa'])->default('milik_perusahaan');
            $table->string('nama_vendor_sewa')->nullable(); // Jika sewa
            $table->decimal('biaya_sewa_bulanan', 15, 2)->nullable();
            $table->foreignId('lokasi_id')->constrained('lokasi')->cascadeOnDelete();
            $table->integer('odometer_km')->default(0);
            $table->integer('kapasitas_tangki')->default(60); // Liter
            $table->decimal('konsumsi_bbm_per_liter', 5, 2)->default(10.00); // KM per liter
            $table->date('tanggal_servis_terakhir')->nullable();
            $table->date('tanggal_servis_berikutnya')->nullable();
            $table->enum('status_kendaraan', ['tersedia', 'digunakan', 'perbaikan'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};

