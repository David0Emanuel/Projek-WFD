<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksis';

    protected $fillable = [
        'user_id',
        'kamar_id',
        'total',
        'status_transaksi',
        'type',
        'expired_at',
        'angka_meteran',
        'foto_meteran',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'total' => 'decimal:2',
        'angka_meteran' => 'integer',
    ];

    /**
     * Relasi: Satu transaksi dimiliki oleh satu User (Tenant/Visitor).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Satu transaksi terikat pada satu Kamar.
     */
    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}