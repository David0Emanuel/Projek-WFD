<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamars'; 
    protected $fillable = ['kos_id', 'nomor', 'status', 'tipe_kamar', 'harga', 
        'foto_kamar', 'spesifikasi', 'fasilitas'];

    public function kos() {
        return $this->belongsTo(Kos::class);
    }
    public function tenant() {
        return $this->hasOne(User::class, 'kamar_id');
    }

    public function maintenanceTikets()
    {
        return $this->hasMany(MaintenanceTiket::class);
    }


    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
