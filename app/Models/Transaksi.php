<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel secara eksplisit jika nama tabel Anda berbeda dari konvensi
    protected $table = 'transaksis';

    // 2. Daftarkan kolom yang boleh diisi secara massal (Mass Assignment)
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

    // 3. Casting tipe data otomatis (Opsional, sangat berguna untuk enkapsulasi data)
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