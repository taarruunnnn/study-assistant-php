<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;
use App\Session;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule Laravels builtin scheduling object
     * 
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Checks and marks incomplete schedules
        $schedule->call(
            function () {
                $sessions = Session::all();
                $today = Carbon::today();
                foreach ($sessions as $session) {
                    $date = new Carbon($session->date);
                    $status = $session->status;
                    if ($date->lessThan($today) && $status == "incomplete") {
                        $session->status = "failed";
                        $session->save();
                    }
                }
            }
        )->twiceDaily(8, 12);

        // Commands python to retrain prediction modules twice a day
        $schedule->call(
            function() {
                retrainModels();
            }
        )->twiceDaily(8, 12);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
