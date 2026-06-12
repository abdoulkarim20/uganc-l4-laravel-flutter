<?php

use App\Http\Controllers\Apis\ClientController;
use App\Http\Controllers\Apis\MecanicienController;
use App\Http\Controllers\Apis\ReparationController;
use App\Http\Controllers\Apis\VehiculeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('clients', ClientController::class)->names('api.clients');
Route::apiResource('mecaniciens', MecanicienController::class)->names('api.mecaniciens');
Route::apiResource('reparations', ReparationController::class)->names('api.reparations');
Route::apiResource('vehicules', VehiculeController::class)->names('api.vehicules');
