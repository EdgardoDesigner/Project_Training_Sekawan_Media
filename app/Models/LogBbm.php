<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogBbm extends Model
{
    use HasFactory;

    protected $table = 'log_bbm';

    protected $fillable = [
        'pemesanan_id',
        'kendaraan_id',
        'jumlah_liter',
        'total_biaya',
        'odometer_awal',
        'odometer_akhir',
        'tanggal_isi',
    ];

    protected $casts = [
        'tanggal_isi' => 'date',
        'jumlah_liter' => 'decimal:2',
        'total_biaya' => 'decimal:2',
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }
}

