<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Module;

/**
 * TypeAhead Controller is used to respond to AJAX calls sent
 * from the TypeAhead Javascript function for autocompleting textboxes
 */
class TypeaheadController extends Controller
{
    /**
     * Universities Function
     * 
     * Used to query the users database to obtain a list of 
     * Universities simillar to the queried university name
     *
     * @param Request $request Request object received via AJAX POST
     * 
     * @return Response
     */
    public function universities(Request $request)
    {
        $query = User::where('university', 'ILIKE', '%'.$request->q.'%')->groupBy('university')->pluck('university');
        return response()->json($query);
    }

    /**
     * Modules Function
     * 
     * User to query the modules databse to obtain a list of
     * Modules simillar to the queried module name
     *
     * @param Request $request Request object received via AJAX POST
     * 
     * @return Respomse
     */
    public function modules(Request $request)
    {
        $query = Module::where('name', 'ILIKE', '%'.$request->q.'%')->groupBy('name')->pluck('name');
        return response()->json($query);
    }
}
