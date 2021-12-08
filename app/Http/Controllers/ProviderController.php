<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\Provider;

class ProviderController extends Controller
{
    public function index()
    {
        return ProviderResource::collection(
            Provider::simplePaginate()
        );
    }

    public function single(Provider $provider)
    {
        return new ProviderResource($provider);
    }
}
