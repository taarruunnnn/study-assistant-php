<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSchedule;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function store(StoreSchedule $request){
        
        $req = $request->persist();

        return $req;

        // if ($req)
        // {
        //     $data = schedule_retriever();
        //     return view('schedules.show', compact('data'));
        // }
        // else
        // {   
        //     $data = schedule_retriever();
        //     session()->flash('message','Schedule Already Exists');
        //     return view('schedules.show', compact('data'));
        // }

        
    }

    public function show(){

        // $data = schedule_retriever();

        // return view('schedules.show', compact('data'));

        return view('schedules.show');
    }
}
