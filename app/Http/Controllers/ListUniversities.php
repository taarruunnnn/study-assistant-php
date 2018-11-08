<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ListUniversities extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $query = User::where('university', 'LIKE', '%'.$request->q.'%')->pluck('university');
        return response()->json($query);
    }
}
