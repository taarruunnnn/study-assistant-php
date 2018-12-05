<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSchedule;
use App\Http\Requests\UpdateSchedule;

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

    public function store(StoreSchedule $request)
    {
        
        $request->persist();
        session()->flash('message','Schedule Created');
        return redirect()->route('schedules.show');
        
    }

    public function show()
    {
        $user = Auth::user();
        $data = schedule_retriever();

        if ($schedule = $user->schedule)
        {
            $modules = $schedule->modules;
            return view('schedules.show', compact('data', 'schedule', 'modules'));
        } 
        else 
        {
            return view('schedules.show', compact('data'));
        }
        
    }

    public function update(UpdateSchedule $request)
    {
        $request->persist();
        session()->flash('message','Schedule Updated');
        return redirect()->route('schedules.show');
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->schedule->sessions()->delete();
        $user->schedule->delete();
        session()->flash('message','Schedule Deleted');
        return back();
    }
}
