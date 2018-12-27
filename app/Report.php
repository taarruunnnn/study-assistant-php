<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'no_modules', 'sessions_completed', 'sessions_missed', 'sessions_incomplete', 'progress', 'sessions', 'time_spent'
    ];

    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
