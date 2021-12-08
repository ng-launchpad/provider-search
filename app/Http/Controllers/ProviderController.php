<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        $providers = Provider::all();

        return ProviderResource::collection($providers);
    }

    public function single(Provider $provider)
    {
        return new ProviderResource($provider);
    }
}
