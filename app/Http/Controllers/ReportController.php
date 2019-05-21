<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp;
use Psr\Http\Message\ServerRequestInterface;
use App\Http\Requests\StoreReport;
use App\Report;
use function GuzzleHttp\json_encode;
use Spatie\Activitylog\Models\Activity;
use App\CompletedModule;

/**
 * Report controller is used to handle functions
 * related to App\Report
 */
class ReportController extends Controller
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
     * Show Function
     * 
     * Shows the reporting page to user
     * with queried data
     *
     * @return View
     */
    public function show()
    {
        $reports = [];
        
        if ($schedule = Auth::user()->schedule) {
            $reports = $schedule->reports;
        } else {
            $schedule = null;
        }

        $user_id = Auth::user()->id;

        $logs = Activity::all()->where('causer_id', $user_id);
        if (count($logs) == 0) {
            $logs = null;
        }
        if (count($reports) == 0) {
            $reports = null;
        }

        $archived = Auth::user()->completed_modules->where('grade', null);

        return view(
            'reports.show', compact('reports', 'archived', 'logs', 'schedule')
        );
    }

    /**
     * View Function
     * 
     * Shows data about individual reports
     *
     * @param Report $report GET Request data is used to gather a user report
     * 
     * @return View
     */
    public function view(Report $report)
    {
        $modulesCount = $report->no_modules;
        $sessionsComplete = $report->sessions_completed;
        $sessionsMissed = $report->sessions_missed;
        $sessionsIncomplete = $report->sessions_incomplete;
        $progress = $report->progress;
        $sessionsDb = $report->sessions;
        $timeSpend = $report->time_spent;
        $studyTimes = $report->study_times;
        $moduleratings = $report->module_ratings;
        $predictions = $report->predictions;
        $sessiondetails = $report->sessiondetails;
        $date = $report->created_at;

        $data = array(
            "sessions" => json_decode($sessionsDb),
            "comparedtime" => json_decode($timeSpend),
            "studytimes" => json_decode($studyTimes),
            "moduleratings" => json_decode($moduleratings),
            "predictions" => json_decode($predictions),
            "sessiondetails" => json_decode($sessiondetails),
        );

        $data = json_encode($data);
        $live = false;

        return view(
            'reports.report', compact(
                'live', 'modulesCount', 'sessionsComplete', 'sessionsMissed', 
                'sessionsIncomplete', 'progress', 'sessionsDb', 
                'timeSpend', 'date', 'data'
            )
        );
    }

    /**
     * Generate Function
     * 
     * User data is gathered to generate a report 
     * and send it to user
     *
     * @return View
     */
    public function generate()
    {
        if ($schedule = Auth::user()->schedule) {
            $modules = $schedule->modules;
            $sessions = $schedule->sessions;

            $total_session_count = count($sessions);
            $completed_session_count = count(
                $sessions->where('status', 'completed')
            );
            $progress = round(
                (($completed_session_count/$total_session_count) * 100), 2
            );

            $live = true;

            $predictions = array();
            foreach ($modules as $module) {
                $grade = $this->predict($schedule->id, $module->name);
                array_push($predictions, array($module->name, $grade));
            }

            $grades = json_encode($predictions);
        } else {
            session()->flash('error', 'Schedule not created to generate reports');
            return redirect()->route('reports.show'); 
        }
        

        return view(
            'reports.report', compact(
                'schedule', 'modules', 'sessions', 'progress', 'live', 'grades'
            )
        );
    }

    /**
     * Analyze Request
     *
     * @param Request $request Request object received via an AJAX POST
     * 
     * @return string JSON is decoded into a string and sent
     */
    public function analyze(Request $request)
    {
        $schedule = 'schedule='.$request->schedule;

        $client = new Client(['base_uri' => config('python.host')]);
        $response = $client->request('GET', 'reports/generate', ['query' => $schedule]);
        $results = json_decode($response->getBody(), true);
        return $results;
    }

    /**
     * Prediction function
     * 
     * Sends request to Python backend with arguements
     * Receives predicted grade and returns it
     *
     * @param int    $sched      Schedule Id of authenticated users schedule
     * @param string $moduleName Module name
     * 
     * @return string
     */
    public function predict($sched, $moduleName)
    {
        $prefsPath = storage_path('app/public/preferences.json');
        $jsonFile = file_get_contents($prefsPath);
        $jsonFile = json_decode($jsonFile, true);

        $threshold = $jsonFile['threshold'];
        $completed = CompletedModule::count();

        if ($threshold <= $completed){
            $json = array(
                'params' => $jsonFile['params'],
                'algorithm' => $jsonFile['algorithm'],
                'sched' => $sched,
                'module' => $moduleName,
                'user' => Auth::user()->id
            );
            try {
                error_log(config('python.host'));
                $client = new Client(['base_uri' => config('python.host')]);
                $response = $client->request('POST', 'analysis/predict', ['json' => $json]);
                $results = json_decode($response->getBody(), true);
            } catch (GuzzleHttp\Exception\ConnectException $e) {
                $results = null;
            }
            
            return $results;
        }

        return null;
    }

    /**
     * Save Function
     * 
     * Report is saved on user request
     *
     * @param StoreReport $request Request object received via POST
     * 
     * @return Redirect
     */
    public function save(StoreReport $request)
    {
        $request->persist();
        session()->flash('message', 'Report Saved');
        return redirect()->route('reports.show');
    }

    /**
     * Destroy Function
     * 
     * Reports and Logs belonging to the user
     * are deleted as per the request of the user
     *
     * @return void
     */
    public function destroy()
    {   
        if ($schedule = Auth::user()->schedule) {
            if ($reports = $schedule->reports()) {
                $reports->delete();
            }
        }

        $user_id = Auth::user()->id;
        $logs = Activity::where('causer_id', $user_id);
        if ($logs) {
            $logs->get()->each->delete();
        }

        session()->flash('message', 'Successfully deleted reports & logs');
        return redirect()->route('reports.show');
    }
}
