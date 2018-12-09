<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSchedule;
use App\Http\Requests\UpdateSchedule;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;

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
        $modules = $request['modules'];
        $query = http_build_query($modules, null, '&');
        $string = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $query);

        $client = new Client(['base_uri' => 'http://127.0.0.1:5000']);
        $response = $client->request('GET', '/ratings', ['query' => $string]);
        $dec = json_decode($response->getBody(), true);
        return $dec;
    }

    public function store(StoreSchedule $request)
    {
        
        $request->persist();
        session()->flash('message','Schedule Created');
        return redirect()->route('schedules.show');
        
    }

    public function show()
    {
        $user = Auth::user();
        $data = schedule_retriever();

        if ($schedule = $user->schedule)
        {
            $modules = $schedule->modules;
            return view('schedules.show', compact('data', 'schedule', 'modules'));
        } 
        else 
        {
            return view('schedules.show', compact('data'));
        }
        
    }

    public function update(UpdateSchedule $request)
    {
        $request->persist();
        session()->flash('message','Schedule Updated');
        return redirect()->route('schedules.show');
    }

    public function destroy()
    {
        $user = Auth::user();
        $user->schedule->modules()->delete();
        $user->schedule->sessions()->delete();
        $user->schedule->delete();
        session()->flash('message','Schedule Deleted');
        return back();
    }
}
