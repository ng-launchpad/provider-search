<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    const LOAD_RELATIONS = [
        'hospitals',
        'languages',
        'locations.addressState',
        'network',
        'specialities',
    ];

    /**
     * Get list of providers
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $request->validate([
            'network_id' => 'required',
            'state_id'   => 'required',
        ]);

        $providers = Provider::query()
            ->with(static::LOAD_RELATIONS)
            ->filter($request->all())
            ->paginateFilter();

        return ProviderResource::collection(
            $providers
        );
    }

    /**
     * Get a single provider
     *
     * @param \App\Models\Provider $provider
     *
     * @return \App\Http\Resources\ProviderResource
     */
    public function single(Provider $provider)
    {
        return new ProviderResource(
            $provider->load(static::LOAD_RELATIONS)
        );
    }
}
