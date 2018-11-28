<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name', 'rating', 'start', 'days'
    ];

    protected $casts = [
        'days' => 'array',
    ];


    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
