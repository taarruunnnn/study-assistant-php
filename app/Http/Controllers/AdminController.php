<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Module;
use App\Schedule;
use App\CompletedModule;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

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

    /**
     * Admin dashboard function
     *
     * @return View
     */
    public function dashboard()
    {
        $users = User::count();
        $modules = Module::count();
        $schedules = Schedule::count();
        $completed = CompletedModule::count();
        return view('admin.dashboard', compact('users', 'modules', 'schedules', 'completed'));
    }

    /**
     * Analyze Request
     * 
     * @return string JSON is decoded into a string and sent back
     */
    public function analyze()
    {
        
        $client = new Client(['base_uri' => 'http://127.0.0.1:5000']);
        $response = $client->request('GET', '/admin');
        $results = json_decode($response->getBody(), true);
        return $results;
    }

    /**
     * Users function that returns list of users
     *
     * @return void
     */
    public function users()
    {
        $users = User::where('type', 'default')
            ->orderBy('id', 'asc')
            ->paginate(15);

        return view('admin.users', compact('users'));
    }

    /**
     * Sends user details to admin
     *
     * @param Request $request
     * @return void
     */
    public function userDetails(Request $request)
    {
        $userId = $request->id;
        $user = User::where('id', $userId)->first();
        if ($schedule = $user->schedule) {
            $schedule_start = $schedule->start;
            $schedule_end = $schedule->end;
        } else {
            $schedule_start = null;
            $schedule_end = null;
        }

        $logs = Activity::all()->where('causer_id', $userId);
        if (count($logs) == 0) {
            $logs = null;
        }

        return response()
            ->json(
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'schedule_start' => $schedule_start,
                    'schedule_end' => $schedule_end, 
                    'logs' => $logs
                ]
            );
    }

    public function userDelete(Request $request)
    {
        $userId = $request->id;
        $user = User::find($userId);
        $user->delete();

        session()->flash('message', 'User Deleted');
        return "Success";
    }
}
