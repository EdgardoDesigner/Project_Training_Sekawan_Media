<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama',
        'email',
        'peran',
        'jabatan',
        'nomor_telepon',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->peran === 'admin';
    }

    public function isPenyetuju(): bool
    {
        return $this->peran === 'penyetuju';
    }

    public function pemesanan(): HasMany
    {
        return $this->hasMany(Pemesanan::class, 'admin_id');
    }

    public function persetujuan(): HasMany
    {
        return $this->hasMany(PersetujuanPemesanan::class, 'penyetuju_id');
    }
}
