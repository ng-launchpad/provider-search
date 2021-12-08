<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use App\Models\State;

class StateController extends Controller
{
    /**
     * Get list of states
     */
    public function index()
    {
        $states = State::all();
        return StateResource::collection(State::all());
    }
}
