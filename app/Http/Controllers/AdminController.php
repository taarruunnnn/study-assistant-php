<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Module;
use App\Schedule;
use App\CompletedModule;
use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;

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
}
