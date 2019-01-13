<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * The session model is used to interact with
 * study sessions created by the system when a 
 * schedule is created by a user.
 */
class Session extends Model
{
    /**
     * Mass Assignable Variables
     *
     * @var array
     */
    protected $fillable = [
        'module', 'date', 'status', 'completed_time'
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
