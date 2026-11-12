<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanServis extends Model
{
    use HasFactory;

    protected $table = 'catatan_servis';

    protected $fillable = [
        'kendaraan_id',
        'tanggal_servis',
        'odometer_km',
        'deskripsi_servis',
        'biaya',
        'bengkel',
    ];

    protected $casts = [
        'tanggal_servis' => 'date',
        'biaya' => 'decimal:2',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}

