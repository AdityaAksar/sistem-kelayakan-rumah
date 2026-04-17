<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class DataRtlh extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_pendataan' => 'date', 
        'aset_rumah_di_lokasi_lain' => 'boolean', 
        'aset_tanah_di_lokasi_lain' => 'boolean'
    ];
    public function user() { return $this->belongsTo(User::class); }
    public function kelurahan() { return $this->belongsTo(Kelurahan::class); }
    public function hasilPrediksi() { return $this->hasOne(HasilPrediksi::class); }
}
