<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksis';
    protected $fillable = ['user_id', 'kamar_id', 'total', 'status_transaksi', 'type', 'expired_at', 'angka_meteran', 'foto_meteran'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function kamar() {
        return $this->belongsTo(Kamar::class);
    }
}
