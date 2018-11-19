<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name', 'start', 'rep'
    ];


    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
