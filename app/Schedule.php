<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'start', 'end'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function sessions()
    {
        return $this->hasMany('App\Session');
    }

    public function modules()
    {
        return $this->hasMany('App\Module');
    }
}
