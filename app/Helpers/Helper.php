<?php
/**
 * This file contains custom helper functions 
 * which are autoloaded using composer.json
 * They are available globally.
 */

use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
use function GuzzleHttp\json_encode;
use GuzzleHttp\Psr7\Request;

if (!function_exists('scheduleRetriever')) {
    /**
     * Schedule Retriever Function
     * 
     * This function retrieves the session data from the database,
     * processes it and returns it in an array.
     * 
     * @param App\User $user
     *
     * @return array
     */
    function scheduleRetriever($user)
    {
        if ($schedule = $user->schedule) {
            $data = [];
            $sessions = $schedule->sessions;

            $colors = array(
                        "#3D62B8", 
                        "#0097A7", 
                        "#4DB6AC", 
                        "#607D8B", 
                        "#1E88E5", 
                        "#BA68C8", 
                        "#673AB7", 
                        "#00BCD4", 
                        "#3F51B5", 
                        "#03A9F4"
                    );
                    
            $x = 0;
            $modules = array();

            foreach ($sessions as $session) {
                if (!in_array($session->module, $modules, true)) {
                    array_push($modules, $session->module);
                }

                $colorKey = array_search($session->module, $modules);

                $color = $colors[$colorKey];
                $x++;
                    
                if ($session['status'] == "incomplete") {
                    $data[] = [
                                'id' => $session->id,
                                'title' => $session->module,
                                'start' => $session->date,
                                'end' => $session->date,
                                'color' => $color,
                                'description' => 'session',
                                'status' => 'Incomplete'
                            ];
                } elseif ($session['status'] == "failed") {
                    $data[] = [
                                'id' => $session->id,
                                'title' => $session->module,
                                'start' => $session->date,
                                'end' => $session->date,
                                'color' => '#D7302A',
                                'description' => 'session',
                                'status' => 'Failed'
                            ];
                } elseif ($session['status'] == "completed") {
                    $data[] = [
                                'id' => $session->id,
                                'title' => $session->module,
                                'start' => $session->date,
                                'end' => $session->date,
                                'color' => '#038103',
                                'description' => 'session',
                                'status' => 'Completed'
                            ];
                }
            }

            if ($events = $schedule->events) {
                foreach ($events as $event) {
                    $data[] = [
                                'id' => $event->id,
                                'title' => $event->description,
                                'start' => $event->date,
                                'end' => $event->date,
                                'color' => '#562424',
                                'description' => 'event',
                                'className' => 'calendarEvent'
                            ];
                }
            }
            return $data;
        }
    }
}


if (!(function_exists('failedSessionMarker'))){
    /**
     * Failed Session Marker
     *
     * Marks incomplete sessions with a date before today
     * as failed sessions
     * 
     * @param App\User $user
     * 
     * @return void
     */
    function failedSessionMarker($user){
        if ($schedule = $user->schedule) {
            $sessions = $schedule->sessions;
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
    }
}


if (!(function_exists('retrainModels'))){
    /**
     * Retrain Models
     * 
     * Requests the Python backend to intiate a training sequence of the
     * prediction modules. This function does this Asynchronously because
     * training is time consuming.
     *
     * @return void
     */
    function retrainModels(){

        $prefsPath = storage_path('app/public/preferences.json');
        $jsonFile = file_get_contents($prefsPath);
        $jsonFile = json_decode($jsonFile, true);
        
        $json = array(
            'params' => $jsonFile['params'],
        );

        try {
            $client = new Client([
                'base_uri' => config('python.host'), 
                'timeout' => 0.1
            ]);
            $promise = $client->postAsync('analysis/retrain', [
                'json' => $json
            ]);
            $promise->wait();
        } catch (GuzzleHttp\Exception\ConnectException $e) {
            $results = null;
        }
    }
}
