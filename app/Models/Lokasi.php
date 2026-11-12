<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'kode_lokasi',
        'nama_lokasi',
        'tipe_lokasi',
        'alamat',
    ];

    public function kendaraan(): HasMany
    {
        return $this->hasMany(Kendaraan::class, 'lokasi_id');
    }

    public function pemesananTujuan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'lokasi_tujuan_id');
    }
}

