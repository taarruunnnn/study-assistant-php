<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * The module class is used to interact with modules
 * or subjects.
 */
class Module extends Model
{
    /**
     * Mass Assignable Variables
     *
     * @var array
     */
    protected $fillable = [
        'name', 'rating', 'grade'
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
