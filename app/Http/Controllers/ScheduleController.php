<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSchedule;
use App\Http\Requests\UpdateSchedule;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use App\Session;
use App\Schedule;
use App\CompletedModule;
use Illuminate\Support\Carbon;

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

    public function analyze(Request $request)
    {
        $module = 'module='.$request['module'];
        
        $client = new Client(['base_uri' => 'http://127.0.0.1:5000']);
        $response = $client->request('GET', '/ratings', ['query' => $module]);
        $results = json_decode($response->getBody(), true);
        return $results;
    }

    public function store(StoreSchedule $request)
    {
        $user = Auth::user();
        $schedule = new Schedule();
        $schedule->createSchedule($user, $request);
        session()->flash('message','Schedule Created');
        return redirect()->route('schedules.show');
        
    }

    public function show()
    {
        $user = Auth::user();
        $data = schedule_retriever();
        $toarchive = false;

        if ($schedule = $user->schedule)
        {
            $today = Carbon::today();
            $schedule_end = new Carbon($schedule->end);
            

            if($today->greaterThanOrEqualTo($schedule_end))
            {
                $toarchive = true;
            }

            $modules = $schedule->modules;
            return view('schedules.show', compact('data', 'schedule', 'modules', 'toarchive'));
        } 
        else 
        {
            return view('schedules.show', compact('data', 'toarchive'));
        }
        
    }

    public function update(UpdateSchedule $request)
    {
        $request->persist();
        session()->flash('message','Schedule Updated');
        return redirect()->route('schedules.show');
    }

    public function move(Request $request)
    {
        $events = $request->events;
        foreach ($events as $event) 
        {
            $id = $event['id'];
            $date = $event['date'];
            $session = Session::findOrFail($id);
            $session->date = $date;
            $session->status = "incomplete";
            $session->save();
        }
        session()->flash('status', 'Task was successful!');
        return "Successfully moved";
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->schedule->modules()->delete();
        $user->schedule->sessions()->delete();
        $user->schedule->reports()->delete();
        $user->schedule->delete();
        session()->flash('message','Schedule Deleted');
        return back();
    }

    public function archive()
    {
        $completedModule = new CompletedModule();
        $completedModule->archive();

        session()->flash('message','Schedule Archived');
        return redirect()->route('schedules.show');
    }

}
