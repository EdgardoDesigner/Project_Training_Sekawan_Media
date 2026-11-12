<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'nomor_polisi',
        'nomor_lambung',
        'merk_model',
        'jenis_kendaraan',
        'kepemilikan',
        'nama_vendor_sewa',
        'biaya_sewa_bulanan',
        'lokasi_id',
        'odometer_km',
        'kapasitas_tangki',
        'konsumsi_bbm_per_liter',
        'tanggal_servis_terakhir',
        'tanggal_servis_berikutnya',
        'status_kendaraan',
    ];

    protected $casts = [
        'tanggal_servis_terakhir' => 'date',
        'tanggal_servis_berikutnya' => 'date',
        'biaya_sewa_bulanan' => 'decimal:2',
        'konsumsi_bbm_per_liter' => 'decimal:2',
    ];

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'kendaraan_id');
    }

    public function logBbm(): HasMany
    {
        return $this->hasMany(LogBbm::class, 'kendaraan_id');
    }

    public function catatanServis(): HasMany
    {
        return $this->hasMany(CatatanServis::class, 'kendaraan_id');
    }
}

