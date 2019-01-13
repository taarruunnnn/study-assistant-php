<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUser;
use Illuminate\Http\Request;

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
        $user->country = $request->input('country');
        $user->university = $request->input('university');
        $user->major = $request->input('major');
        $user->birth = $request->input('birth');
        $user->gender = $request->input('gender');

        if (! $request->input('password') == '') {
            $user->password = bcrypt($request->input('password'));
        }

        $user->save();

        session()->flash('message', 'User Updated');

        return back();
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        session()->flash('message', 'User Deleted');

        return view('welcome');
    }
}
