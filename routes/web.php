<?php

use App\Http\Controllers\CaddyController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// caddy route for local ssl
Route::get('/caddy-check', [CaddyController::class, 'check']);

Route::get('{any}', [HomeController::class, 'index'])
    // ->where('any','.*')
    ->where('any', '^(?!caddy-check).*$');
