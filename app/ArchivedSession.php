<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArchivedSession extends Model
{
    protected $fillable = [
        'module', 'date', 'status'
    ];

    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
