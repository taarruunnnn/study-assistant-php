<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSchedule;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use App\Session;
use App\Schedule;
use App\CompletedModule;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

/**
 * Schedule Controller is used to handle functions related to
 * App\Schedule
 */
class ScheduleController extends Controller
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
     * Create Function
     * 
     * The view to create schedules is returned
     *
     * @return View
     */
    public function create()
    {
        if ($schedule = Auth::user()->schedule) {
            session()->flash('error', 'Schedule already exists');
            return redirect()->route('schedules.show'); 
        }
        return view('schedules.create');
    }

    /**
     * Analyze Function
     * 
     * On user request, modules are analyzed by sending them to
     * the Python Web Server and the response is forwarded to user
     *
     * @param Request $request Request object received via AJAX POST
     * 
     * @return string JSON is decoded into string
     */
    public function analyze(Request $request)
    {
        $module = 'module='.$request['module'];
        
        $client = new Client(['base_uri' => config('python.host')]);
        $response = $client->request('GET', 'module/details', ['query' => $module]);
        $results = json_decode($response->getBody(), true);
        return $results;
    }

    /**
     * Store Function
     * 
     * Schedule is created using createSchedule function
     * is App\Schedule
     *
     * @param StoreSchedule $request Request object received via POST
     * 
     * @return Redirect
     */
    public function store(StoreSchedule $request)
    {
        $user = Auth::user();
        
        $schedule = new Schedule();
        $schedule->createSchedule($user, $request);

        activity()->log('Created Schedule');

        session()->flash('message', 'Schedule Created');
        return redirect()->route('schedules.show');
    }

    /**
     * Show Function
     * 
     * Schedule data is showed to the user
     *
     * @return View
     */
    public function show()
    {
        $user = Auth::user();
        failedSessionMarker($user);   
        $data = scheduleRetriever($user);
        $toarchive = false;

        if ($schedule = $user->schedule) {
            $today = Carbon::today();
            $schedule_end = new Carbon($schedule->end);
        
            if ($today->greaterThanOrEqualTo($schedule_end)) {
                $toarchive = true;
            }

            $modules = $schedule->modules;
            return view('schedules.show', compact('data', 'schedule', 'modules', 'toarchive'));
        } else {
            return view('schedules.show', compact('data', 'toarchive'));
        }
    }

    /**
     * Sessions Function
     * 
     * Returns schedule sessions in json format
     *
     * @return Response
     */
    public function sessions()
    {
        $user = Auth::user();
        $data = scheduleRetriever($user);
        return response()->json($data);
    }
    
    /**
     * Schedule Update
     * 
     * Schedule is updated upon user request
     *
     * @param UpdateSchedule $request Request object received via POST
     * 
     * @return Redirect
     */
    public function update(StoreSchedule $request)
    {
        $user = Auth::user();
        
        if ($sched = $user->schedule) {
            $sched->modules()->delete();
            $sched->sessions()->delete();
            $sched->reports()->delete();
            $sched->delete();
        }
        
        $schedule = new Schedule();
        $schedule->createSchedule($user, $request);

        activity()->log('Modified Schedule');

        session()->flash('message', 'Schedule Modified');
        return back();
    }


    /**
     * Session Moving Function
     * 
     * Session is moved as per user request
     *
     * @param Request $request Request object received via POST
     * 
     * @return string
     */
    public function move(Request $request)
    {
        $session = Session::find($request->id);
        $session->date = $request->date;
        $session->status = 'incomplete';
        $session->save();

        activity()->log('Moved Session');

        session()->flash('status', 'Session moved successfully!');
        return back();
    }

    /**
     * Destroy Function
     * 
     * Schedule data is deleted upon user request
     *
     * @return Redirect
     */
    public function destroy()
    {
        $user = Auth::user();
        $user->schedule->modules()->delete();
        $user->schedule->sessions()->delete();
        $user->schedule->reports()->delete();
        $user->schedule->delete();

        activity()->log('Deleted Schedule');

        session()->flash('message', 'Schedule Deleted');
        return back();
    }

    /**
     * Archive Function
     * 
     * Once schedule is over, modules are archived
     *
     * @return Redirect
     */
    public function archive()
    {
        $completedModule = new CompletedModule();
        $completedModule->archive();

        activity()->log('Archived Schedule');

        session()->flash('message', 'Schedule Archived');
        return redirect()->route('schedules.show');
    }

    /**
     * Archive Update
     * 
     * Grades of the archived modules are updated
     *
     * @param Request $request Request object received via AJAX POST
     * 
     * @return string
     */
    public function archiveUpdate(Request $request)
    {

        foreach ($request->module as $key => $value) {
            if ($value != "null") {
                $module = CompletedModule::find($key);
                $module->grade = $value;
                $module->save();
            }
        }

        session()->flash('message', 'Grades Updated');
        return back();
    }

}
