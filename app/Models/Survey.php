<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;
    
    protected $table = 'surveys';

    protected $fillable = [
        'user_id',
        'kos_id',
        'waktu_survey',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'waktu_survey' => 'datetime',
    ];

    /**
     * RELASI DATABASE
     */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }
}