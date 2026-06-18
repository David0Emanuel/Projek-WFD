<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';
    protected $fillable = ['judul', 'isi', 'kos_id'];

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }
}