<?php

namespace App\Http\Controllers;

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

    public function single(int $id)
    {
        return json_encode([
            'data' => [
                'id'    => $id,
                'label' => 'Provider ' . $id,
            ],
        ]);
    }
}
