<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class ModelVersion extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean'];
    public function user() { return $this->belongsTo(User::class); }
    public function hasilPrediksis() { return $this->hasMany(HasilPrediksi::class); }
}
