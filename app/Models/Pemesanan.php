<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pemesanan extends Model
{
    use HasFactory;

    protected $table = 'pemesanan';

    protected $fillable = [
        'kode_pemesanan',
        'admin_id',
        'nama_pemesan',
        'divisi_pemesan',
        'keperluan',
        'kendaraan_id',
        'driver_id',
        'lokasi_tujuan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_pemesanan',
        'alasan_penolakan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function lokasiTujuan(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_tujuan_id');
    }

    public function persetujuan(): HasMany
    {
        return $this->hasMany(PersetujuanPemesanan::class, 'pemesanan_id')->orderBy('level_persetujuan', 'asc');
    }

    public function persetujuanLevel1()
    {
        return $this->hasOne(PersetujuanPemesanan::class, 'pemesanan_id')->where('level_persetujuan', 1);
    }

    public function persetujuanLevel2()
    {
        return $this->hasOne(PersetujuanPemesanan::class, 'pemesanan_id')->where('level_persetujuan', 2);
    }

    public function logBbm(): HasMany
    {
        return $this->hasMany(LogBbm::class, 'pemesanan_id');
    }
}

