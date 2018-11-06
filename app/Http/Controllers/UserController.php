<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUser;

class UserController extends Controller
{
    public function __construct()
    {
        $this-> middleware('auth');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUser $request)
    {
        $user = Auth::user();

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ( ! $request->input('password') == '')
        {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        session()->flash('message','User Updated');

        return back();
    }
}
