<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * The event model is used to interact with special events
 * such as assignment submissions.
 */
class Event extends Model
{
    /**
     * Mass Assignable Variables
     *
     * @var array
     */
    protected $fillable = [
        'date', 'description',
    ];

    /**
     * Schedule Relationship
     *
     * @return Relationship
     */
    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }
}
