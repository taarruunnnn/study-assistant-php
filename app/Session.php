<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'module', 'date', 'status', 'completed_time'
    ];

    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
