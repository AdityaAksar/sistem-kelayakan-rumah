<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $guarded = ['id'];
    public function kecamatan() { return $this->belongsTo(Kecamatan::class); }
    public function dataRtlhs() { return $this->hasMany(DataRtlh::class); }
}
