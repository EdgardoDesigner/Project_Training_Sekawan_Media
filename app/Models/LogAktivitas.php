<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'pengguna_id',
        'aksi',
        'deskripsi',
        'alamat_ip',
        'perangkat',
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    public static function catat(string $aksi, string $deskripsi, ?int $penggunaId = null): self
    {
        return self::create([
            'pengguna_id' => $penggunaId ?? Auth::id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'alamat_ip' => request()->ip(),
            'perangkat' => substr(request()->userAgent() ?? 'CLI/Unknown', 0, 255),
        ]);
    }
}

