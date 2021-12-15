<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpecialityController extends Controller
{
    /**
     * Get list of cities for a given Network and State
     */
    public function index(Request $request)
    {
        $request->validate([
            'network_id' => 'required',
            'state_id'   => 'required',
        ]);

        return [];
    }
}
