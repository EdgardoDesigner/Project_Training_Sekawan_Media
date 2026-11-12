<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersetujuanPemesanan extends Model
{
    use HasFactory;

    protected $table = 'persetujuan_pemesanan';

    protected $fillable = [
        'pemesanan_id',
        'penyetuju_id',
        'level_persetujuan',
        'status_persetujuan',
        'catatan',
        'waktu_aksi',
    ];

    protected $casts = [
        'waktu_aksi' => 'datetime',
        'level_persetujuan' => 'integer',
    ];

    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }

    public function penyetuju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penyetuju_id');
    }
}

