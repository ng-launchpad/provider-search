<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/states', [StateController::class, 'index'])->name('api.states.index');
Route::get('/providers', [ProviderController::class, 'index'])->name('api.providers.index');
Route::get('/providers/{provider}', [ProviderController::class, 'single'])->name('api.providers.single');
Route::get('/cities', [CityController::class, 'index'])->name('api.cities.index');
