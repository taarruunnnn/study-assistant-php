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
use Illuminate\Support\Facades\Storage;

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
     * Sends analysis request to Python backend to populate
     * admin dashboard
     * 
     * @return string JSON is decoded into a string and sent back
     */
    public function analyze()
    {
        
        $client = new Client(['base_uri' => config('python.host')]);
        $response = $client->request('GET', 'admin/analytics');
        $results = json_decode($response->getBody(), true);
        return $results;
    }

    /**
     * Displays predictions page
     *
     * @return View
     */
    public function predictions()
    {
        $prefsPath = storage_path('app/public/preferences.json');
        $json = file_get_contents($prefsPath);
        $completed = CompletedModule::count();

        return view('admin.predictions', compact('json', 'completed'));
    }

    /**
     * Receives prediction data from Python backend
     * 
     * @param Request $request POST request data
     *
     * @return JSON
     */
    public function predictionAccuracy(Request $request)
    {
        $params = json_encode($request->params);
        
        $client = new Client(['base_uri' => config('python.host')]);
        $response = $client->request('POST', 'admin/accuracy', ['json' => $params]);
        $results = json_decode($response->getBody(), true);
        
        return $results;
    }

    /**
     * Saves prediction preferences to json file
     *
     * @param Request $request POST data
     * 
     * @return string
     */
    public function predictionSave(Request $request)
    {
        $params = json_decode($request->params);
        $algorithm = $request->algorithm;
        $threshold = $request->threshold;

        if ($threshold > 1000000 || $threshold < 10) 
        {
            session()->flash('error', 'Invalid Threshold Value');
            return back();
        }
        
        $prefs = array(
            'params' => $params,
            'algorithm' => $algorithm,
            'threshold' => $threshold
        );

        $jsonString = json_encode($prefs, JSON_PRETTY_PRINT);
        $prefsPath = storage_path('app/public/preferences.json');
        if (file_put_contents($prefsPath, stripslashes($jsonString))) {
            retrainModels();
            session()->flash('message', 'Preferences Saved');
            return back();
        } else {
            session()->flash('message', 'Error Saving Preferences');
            return back();
        }
        
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
     * @param Request $request POST data
     * 
     * @return response
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

    /**
     * Delete User
     *
     * @param Request $request POST data
     * 
     * @return string
     */
    public function userDelete(Request $request)
    {
        $userId = $request->id;
        $user = User::find($userId);
        
        $user->schedule->modules()->delete();
        $user->schedule->sessions()->delete();
        $user->schedule->reports()->delete();
        $user->schedule->delete();
        $user->delete();

        session()->flash('message', 'User Deleted');
        return "Success";
    }
}
