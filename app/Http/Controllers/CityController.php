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

        $result = DB::query()
            ->from('location_provider')
            ->leftJoin('locations', 'location_provider.location_id', '=', 'locations.id')
            ->leftJoin('providers', 'location_provider.provider_id', '=', 'providers.id')
            ->where('locations.version', '=', Setting::version())
            ->where('locations.address_state_id', '=', $request->get('state_id'))
            ->where('providers.network_id', '=', $request->get('network_id'))
            ->orderBy('locations.address_city')
            ->distinct()
            ->get(['locations.address_city']);

        return CityResource::collection($result);
    }
}
