<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Shows welcome page to guests
     *
     * @return void
     */
    public function showLoginForm()
    {
        return view('welcome');
    }

    /**
     * Function runs everytime user is authenticated
     * 
     * Function checks if there are any failed sessions
     * and marks them as failed.
     *
     * @param Request $request Authentication request
     * @param User    $user    Current user
     * 
     * @return void
     */
    protected function authenticated(Request $request, $user)
    {
        failedSessionMarker($user);   
    }
}
