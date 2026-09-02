<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
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
        return $this->hasRole('Admin');
    }

    public function isPegawai(): bool
    {
        return $this->hasRole('Pegawai');
    }

    /**
     * Pengguna internal (Admin/Pegawai) dapat melihat konten yang
     * disembunyikan dari pengunjung anonim, misalnya versi "privat".
     */
    public function isInternal(): bool
    {
        return $this->isAdmin() || $this->isPegawai();
    }
}