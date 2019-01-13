<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use App\Http\Requests\StoreReport;
use App\Report;
use function GuzzleHttp\json_encode;
use Spatie\Activitylog\Models\Activity;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        if ($schedule = Auth::user()->schedule)
        {
            $reports = $schedule->reports;
        }
        else
        {
            $schedule = null;
            $reports = null;
        }

        $user_id = Auth::user()->id;

        if (!($logs = Activity::all()->where('causer_id', $user_id))){
            $logs = null;
        }

        $archived = Auth::user()->completed_modules->where('grade', null);

        return view('reports.show', compact('reports', 'archived', 'logs', 'schedule'));
    }

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
        $date = $report->created_at;

        $data = array(
            "sessions" => json_decode($sessionsDb),
            "comparedtime" => json_decode($timeSpend),
            "studytimes" => json_decode($studyTimes)
        );

        $data = json_encode($data);
        $live = false;

        return view('reports.report', compact('live', 'modulesCount', 'sessionsComplete', 'sessionsMissed', 'sessionsIncomplete', 'progress', 'sessionsDb', 'timeSpend', 'date', 'data'));
    }

    public function generate()
    {
        if ($schedule = Auth::user()->schedule)
        {
            $modules = $schedule->modules;
            $sessions = $schedule->sessions;

            $total_session_count = count($sessions);
            $completed_session_count = count($sessions->where('status', 'completed'));
            $progress = round((($completed_session_count/$total_session_count) * 100), 2);

            $live = true;
        }
        

        return view('reports.report', compact('schedule', 'modules', 'sessions', 'progress', 'live'));
    }

    public function analyze(Request $request)
    {
        $schedule = 'schedule='.$request->schedule;

        $client = new Client(['base_uri' => 'http://127.0.0.1:5000']);
        $response = $client->request('GET', '/reports', ['query' => $schedule]);
        $results = json_decode($response->getBody(), true);
        return $results;
    }

    public function save(StoreReport $request)
    {
        $request->persist();
        session()->flash('message', 'Report Saved');
        return redirect()->route('reports.show');
    }

    public function destroy()
    {
        $reports = Auth::user()->schedule->reports();
        if($reports){
            $reports->delete();
        }

        $user_id = Auth::user()->id;
        $logs = Activity::where('causer_id', $user_id);
        if($logs){
            $logs->get()->each->delete();
        }

        session()->flash('message', 'Successfully deleted reports & logs');
        return redirect()->route('reports.show');
    }
}
