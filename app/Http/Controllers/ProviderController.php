<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProviderIndexRequest;
use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    const LOAD_RELATIONS = [
        'hospitals',
        'languages',
        'locations.state',
        'network',
        'specialities',
    ];

    /**
     * Get list of providers
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(ProviderIndexRequest $request)
    {
        // get providers list
        $providers = Provider::query()
            ->filter($request->all())
            ->orderBy('id')
            ->withVersion($request)
            ->paginateFilter();

        // load relations
        $providers->load(static::LOAD_RELATIONS);

        // return resource collection
        return ProviderResource::collection($providers);
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
        // load relations
        $provider->load(static::LOAD_RELATIONS);

        // load speciality groups from accessor
        // very bad approach
        // @todo move data into the db
        // and make an ordinary relationship
        $provider->load_speciality_groups = true;

        // return provider resource
        return new ProviderResource($provider);
    }
}
