<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    // Definisikan nama tabel
    protected $table = 'kamars';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'nomor_kamar',
        'tipe_kamar',
        'harga_bulanan',
        'status',
    ];

    /**
     * Relasi: Satu kamar bisa memiliki banyak riwayat transaksi/invoice.
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'kamar_id');
    }
}