<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = schedule_retriever();
        
        $modules = array();

        if(!empty($data))
        {
            foreach($data as $d)
            {
                $date = new Carbon($d['start']);
                if($date->isToday())
                {
                    array_push($modules, $d['title']);
                }
            }
        }
        
        return view('dashboard', compact('modules'));
    }
}
