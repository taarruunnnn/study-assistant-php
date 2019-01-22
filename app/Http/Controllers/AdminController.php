<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     * Only authenticated admin users can access its methods
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_admin');
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
