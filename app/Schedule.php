<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'start', 'revision', 'end'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function modules()
    {
        return $this->hasMany('App\Module');
    }
}
