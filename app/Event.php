<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'date', 'description',
    ];

    // Relationships

    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
