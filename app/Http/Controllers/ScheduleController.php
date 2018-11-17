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
        
        $request->persist();
        
        $results = study_scheduler($request);

        return view('schedules.show', compact('results'));
    }

    public function show(){
        $user = Auth::user();
        $schedules = $user->schedules()->get();

        return view('schedules.show', compact('schedules'));
    }
}
