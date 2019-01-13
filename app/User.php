<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * User model is used to interact with the
 * user accounts that represent each user of 
 * the system.
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * Mass Assignable Variables
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'country', 'birth', 'gender', 'university', 'major', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Schedule Relationship
     *
     * @return Relationship
     */
    public function schedule()
    {
        return $this->hasOne('App\Schedule');
    }

    /**
     * Completed Modules Relationship
     *
     * @return Relationship
     */
    public function completed_modules()
    {
        return $this->hasMany('App\CompletedModule');
    }
}
