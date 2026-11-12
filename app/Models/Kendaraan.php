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

    public function getFotoUrlAttribute(): string
    {
        $map = [
            'LV-01' => 'https://commons.wikimedia.org/wiki/Special:FilePath/2020_Toyota_Hilux_Revo_4x4_Double-Cab_2.8_Rocco.jpg?width=800', // Toyota Hilux 4x4 Double Cabin 2.8
            'LV-02' => 'https://commons.wikimedia.org/wiki/Special:FilePath/2021_Mitsubishi_Pajero_Sport_2.4_Dakar_4x2_(Indonesia)_front_view_01.jpg?width=800', // Mitsubishi Pajero Sport 4x4 Dakar
            'LV-03' => 'https://commons.wikimedia.org/wiki/Special:FilePath/2016_Toyota_Fortuner_VRZ_(front),_West_Surabaya.jpg?width=800', // Toyota Fortuner 4x4 VRZ
            'DT-01' => 'https://commons.wikimedia.org/wiki/Special:FilePath/ELS_Hino_Dutro_(2nd)_Topre_refrigerated_truck.jpg?width=800', // Hino Dutro 130 HD Cargo Box
            'DT-02' => 'https://commons.wikimedia.org/wiki/Special:FilePath/2020_Mitsubishi_Fuso_Colt_Diesel_FE_74_HD_K_3.9_(20201212)_01.jpg?width=800', // Mitsubishi Fuso Canter Dump Truck
            'HT-01' => 'https://commons.wikimedia.org/wiki/Special:FilePath/Scania_tipper_lorry_in_Russia.jpg?width=800', // Scania P360 Heavy Mining Dump Truck
        ];

        return $map[$this->nomor_lambung] ?? 'https://commons.wikimedia.org/wiki/Special:FilePath/2020_Toyota_Hilux_Revo_4x4_Double-Cab_2.8_Rocco.jpg?width=800';
    }
}

