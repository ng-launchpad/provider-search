<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
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

        //  @todo (Pablo 2022-01-26) - This could probably be refactored to use model filters

        $seconds = 60 * 60 * 12; // 12 hours
        $result = cache()->remember('cities', $seconds, function () use ($request) {
            return DB::query()
                ->select('locations.address_city')
                ->distinct()
                ->from('location_provider')
                ->leftJoin('locations', 'location_provider.location_id', '=', 'locations.id')
                ->leftJoin('providers', 'location_provider.provider_id', '=', 'providers.id')
                ->where('locations.address_state_id', '=', $request->get('state_id'))
                ->where('providers.network_id', '=', $request->get('network_id'))
                ->orderBy('locations.address_city')
                ->get(['locations.address_city']);
        });

        return CityResource::collection($result);
    }
}
