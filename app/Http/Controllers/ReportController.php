<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;

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
            $modules = $schedule->modules;
            $sessions = $schedule->sessions;

            $total_session_count = count($sessions);
            $completed_session_count = count($sessions->where('status', 'completed'));
            $progress = round((($completed_session_count/$total_session_count) * 100), 2);
        }
        

        return view('reports.show', compact('schedule', 'modules', 'sessions', 'progress'));
    }

    public function analyze(Request $request)
    {
        $schedule = 'schedule='.$request->schedule;

        $client = new Client(['base_uri' => 'http://127.0.0.1:5000']);
        $response = $client->request('GET', '/reports', ['query' => $schedule]);
        $results = json_decode($response->getBody(), true);
        return $results;
    }
}
