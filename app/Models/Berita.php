<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class Berita extends Model
{
    use LogsActivity;

    protected $guarded = ['id'];
    public function user() { return $this->belongsTo(User::class); }
}
