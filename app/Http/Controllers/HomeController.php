<?php

namespace App\Http\Controllers;

use App\Http\Resources\NetworkResource;
use App\Http\Resources\StateResource;
use App\Models\Network;
use App\Models\State;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.home', [
            'state'    => (new StateResource(State::findByCodeOrFail('TX')))->toJson(),
            'networks' => NetworkResource::collection(Network::all())->toJson(),
        ]);
    }
}
