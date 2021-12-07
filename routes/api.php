<?php

use App\Http\Controllers\ProviderController;
use Illuminate\Http\Request;
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

Route::get('/providers', [ProviderController::class, 'index'])->name('providers.index');
Route::get('/providers/{provider}', [ProviderController::class, 'single'])->name('providers.single');
