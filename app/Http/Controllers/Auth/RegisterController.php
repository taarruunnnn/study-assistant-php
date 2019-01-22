<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data Data that is to be validated
     * 
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data, [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'university' => ['required', 'string', 'max:100'],
                'major' => ['required', 'string', 'max:30'],
                'birth' => ['required', 'digits:4', 'integer', 'min:1970'],
                'gender' => ['required', 'string', 'max:1'],
                'country' => ['required', 'string', 'max:2'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data Request data that is used to create user
     * 
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create(
            [
                'name' => $data['name'],
                'email' => $data['email'],
                'country' => $data['country'],
                'birth' => $data['birth'],
                'gender' => $data['gender'],
                'university' => $data['university'],
                'major' => $data['major'],
                'password' => Hash::make($data['password']),
                'type' => User::DEFAULT_TYPE,
            ]
        );
    }
}
