<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreEvent;
use App\Http\Requests\UpdateEvent;
use Illuminate\Support\Facades\Auth;
use App\Event;

/**
 * Event controller is used to handle functions 
 * related to the App\Events model.
 */
class EventController extends Controller
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

    /**
     * Store Function
     * 
     * Used to store events in the database
     *
     * @param StoreEvent $request $request Request object received via GET/POST
     * 
     * @return Redirect
     */
    public function store(StoreEvent $request)
    {
        $schedule = Auth::user()->schedule;

        $event = $schedule->events()->create(
            [
                'date' => $request->eventdate,
                'description' => $request->description
            ]
        );
        
        session()->flash('message', 'Event Created');
        return redirect()->route('schedules.show');
    }

    /**
     * Update Function
     * 
     * Used to update events
     *
     * @param UpdateEvent $request Request object received via GET/POST
     * 
     * @return void
     */
    public function update(UpdateEvent $request)
    {
        $event = Event::find($request->id);
        $event->date = $request->eventdate;
        $event->description = $request->description;
        $event->save();

        session()->flash('message', 'Event Updated');
        return redirect()->route('schedules.show');
    }

    /**
     * Destroy Function
     * 
     * Used to delete event data
     *
     * @param Request $request Request object received via GET/POST
     * 
     * @return void
     */
    public function destroy(Request $request)
    {
        $event = Event::find($request->id);
        $event->delete();

        session()->flash('message', 'Event Deleted');
        return redirect()->route('schedules.show');
    }
}
