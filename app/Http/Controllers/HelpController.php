<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Create a new controller instance.
     * Only authenticated users can access its methods
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('is_user');
    }

    public function index()
    {
        return view('help.index');
    }

    public function show($faq)
    {
        return view('help.faq.'.$faq);
    }
}
