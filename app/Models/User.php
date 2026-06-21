<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'username',
        'nama',
        'email',
        'no_wa',
        'password',
        'role',
        'kos_id',
        'kamar_id',
        'tanggal_mulaiSewa',
        'tanggal_selesaiSewa',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function hasRole($role)
    {
        return $this->role === $role;
    }
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
    
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    // User bisa memiliki banyak tiket keluhan
    public function maintenanceTikets()
    {
        return $this->hasMany(MaintenanceTiket::class);
    }

    // User bisa mengajukan banyak jadwal survey
    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_mulaiSewa' => 'date',      
            'tanggal_selesaiSewa' => 'date',    
        ];
    }

}