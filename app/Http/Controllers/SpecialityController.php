<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpecialityResource;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        //  @todo (Pablo 2022-01-26) - This could probably be refactored to use model filters

        $seconds = 60;
        $result = cache()->remember('specialities', $seconds, function () use ($request) {
            return DB::query()
                ->from('location_provider')
                ->leftJoin('locations', 'location_provider.location_id', '=', 'locations.id')
                ->leftJoin('providers', 'location_provider.provider_id', '=', 'providers.id')
                ->leftJoin('provider_speciality', 'location_provider.provider_id', '=', 'provider_speciality.provider_id')
                ->leftJoin('specialities', 'provider_speciality.speciality_id', '=', 'specialities.id')
                ->where('locations.address_state_id', '=', $request->get('state_id'))
                ->where('providers.network_id', '=', $request->get('network_id'))
                ->whereNotNull('specialities.label')
                ->orderBy('specialities.label')
                ->distinct()
                ->get(['specialities.label']);
        });

        return SpecialityResource::collection($result);
    }
}
