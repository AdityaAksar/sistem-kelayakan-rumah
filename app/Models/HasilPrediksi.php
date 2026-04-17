<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class HasilPrediksi extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];
    protected $casts = [
        'confidence_score' => 'float',
        'predicted_at' => 'datetime',
    ];

    public function dataRtlh()    { return $this->belongsTo(DataRtlh::class); }
    public function modelVersion() { return $this->belongsTo(ModelVersion::class); }
}
