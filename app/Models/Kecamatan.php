<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    protected $guarded = ['id'];
    public function kelurahans() { return $this->hasMany(Kelurahan::class); }
}
