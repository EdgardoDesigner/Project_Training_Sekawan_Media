<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_bbm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemesanan_id')->nullable()->constrained('pemesanan')->nullOnDelete();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->decimal('jumlah_liter', 8, 2);
            $table->decimal('total_biaya', 15, 2);
            $table->integer('odometer_awal')->nullable();
            $table->integer('odometer_akhir')->nullable();
            $table->date('tanggal_isi');
            $table->timestamps();
        });

        Schema::create('catatan_servis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->cascadeOnDelete();
            $table->date('tanggal_servis');
            $table->integer('odometer_km');
            $table->text('deskripsi_servis');
            $table->decimal('biaya', 15, 2)->default(0);
            $table->string('bengkel')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_bbm');
        Schema::dropIfExists('catatan_servis');
    }
};

