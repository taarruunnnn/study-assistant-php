<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEvent;
use App\Http\Requests\UpdateEvent;
use Illuminate\Support\Facades\Auth;
use App\Event;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(StoreEvent $request)
    {
        $schedule = Auth::user()->schedule;

        $event = $schedule->events()->create([
            'date' => $request->eventdate,
            'description' => $request->description
        ]);
        
        session()->flash('message', 'Event Created');
        return redirect()->route('schedules.show');
    }

    public function update(UpdateEvent $request)
    {
        $event = Event::find($request->id);
        $event->date = $request->eventdate;
        $event->description = $request->description;
        $event->save();

        session()->flash('message', 'Event Updated');
        return redirect()->route('schedules.show');
    }

    public function destroy(Request $request)
    {
        $event = Event::find($request->id);
        $event->delete();

        session()->flash('message', 'Event Deleted');
        return redirect()->route('schedules.show');
    }
}
