<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    protected $fillable = ['nama', 'alamat'];

    public function kamars() {
        return $this->hasMany(Kamar::class);
    }

    public function surveys()
    {
        return $this->hasMany(Survey::class);
    }
}
