<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'drivers';

    protected $fillable = [
        'nama_driver',
        'nomor_telepon',
        'nomor_sim',
        'jenis_sim',
        'status_driver',
    ];

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'driver_id');
    }
}

