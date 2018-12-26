<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SessionCount extends Model
{
    protected $fillable = [
        'month', 'count'
    ];

    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
