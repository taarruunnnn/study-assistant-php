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
        $query = User::where('name', 'LIKE', '%'.$request->q.'%')->pluck('name');
        return response()->json($query);
    }
}
