<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->query->get('keywords');

        $query = Provider::query();

        if ($keywords) {
            $query->where('label', 'LIKE', "%$keywords%");
        }

        return ProviderResource::collection(
            $query->simplePaginate()
        );
    }

    public function single(Provider $provider)
    {
        return new ProviderResource($provider);
    }
}
