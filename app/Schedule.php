<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'start', 'end', 'name'
    ];

    public function user()
    {
        return $this->belongsToMany('App\User');
    }

    public function modules()
    {
        return $this->hasMany('App\Module');
    }
}
