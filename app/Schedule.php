<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'start', 'end', 'weekday_hours', 'weekend_hours'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    public function archived_sessions()
    {
        return $this->hasMany('App\ArchivedSession');
    }

    public function modules()
    {
        return $this->hasMany('App\Module');
    }
}
