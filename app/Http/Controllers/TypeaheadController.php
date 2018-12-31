<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Module;

class TypeaheadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function universities(Request $request)
    {
        $query = User::where('university', 'ILIKE', '%'.$request->q.'%')->groupBy('university')->pluck('university');
        return response()->json($query);
    }

    public function modules(Request $request)
    {
        $query = Module::where('name', 'ILIKE', '%'.$request->q.'%')->groupBy('name')->pluck('name');
        return response()->json($query);
    }
}
