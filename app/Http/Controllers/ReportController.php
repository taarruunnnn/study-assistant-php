<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use App\Http\Requests\StoreReport;
use App\Report;
use function GuzzleHttp\json_encode;

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
            return view('reports.show', compact('reports'));
        }
        else
        {
            $reports = "N/A";
            return view('reports.show', compact('reports'));
        }
        
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
        $date = $report->created_at;

        $data = array(
            "sessions" => json_decode($sessionsDb),
            "comparedtime" => json_decode($timeSpend)
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
}