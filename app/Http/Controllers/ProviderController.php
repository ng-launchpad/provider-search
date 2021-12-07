<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function index()
    {
        return json_encode([
            'data' => [
                [
                    'id'    => 1,
                    'label' => 'Provider 1',
                ],
            ],
        ]);
    }

    public function single(Provider $provider)
    {
        return json_encode([
            'data' => [
                'id'    => $provider->id,
                'label' => $provider->label,
            ],
        ]);
    }
}
