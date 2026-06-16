<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTiket extends Model
{
    use HasFactory;

    protected $table = 'maintenance_tikets';
    
    protected $fillable = [
        'user_id',
        'kamar_id',
        'deskripsi',
        'foto',
        'status',
    ];

    /**
     * RELASI DATABASE
     */
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}