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

    const ADMIN_TYPE = 'admin';
    const DEFAULT_TYPE = 'default';

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
     * Checks whether logged in user is an admin or regular user
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->type === self::ADMIN_TYPE;
    }

    /**
     * Checks whether logged in user is not an admin
     *
     * @return boolean
     */
    public function isUser()
    {
        return $this->type === self::DEFAULT_TYPE;
    }

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
