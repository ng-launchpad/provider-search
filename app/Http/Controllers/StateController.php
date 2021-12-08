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
        return StateResource::collection(
            State::all()
        );
    }
}
