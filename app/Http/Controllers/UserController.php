<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateUser;
use Illuminate\Http\Request;

/**
 * User Controller is used to handle functions 
 * related to App\User
 */
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     * Only authenticated users can access its methods
     *
     * @return void
     */
    public function __construct()
    {
        $this-> middleware('auth');
    }

    /**
     * Edit Function
     * 
     * Send a view to user which they can use
     * to edit their information
     *
     * @return View
     */
    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    /**
     * Update Function
     * 
     * User details are updated as requested
     *
     * @param UpdateUser $request Request object received via POST
     * 
     * @return Redirect
     */
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

    /**
     * Destroy Function
     * 
     * Used to delete user
     *
     * @return View
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->delete();

        session()->flash('message', 'User Deleted');

        return redirect()->route('welcome');
    }
}
