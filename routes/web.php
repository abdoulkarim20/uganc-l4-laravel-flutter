<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MecanicienController;
use App\Http\Controllers\ReparationController;
use App\Http\Controllers\VehiculeController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->name('dashboard');

Route::resource('clients', ClientController::class)->except(['show']);
Route::resource('vehicules', VehiculeController::class)->except(['show']);
Route::resource('mecaniciens', MecanicienController::class)->except(['show']);
Route::resource('reparations', ReparationController::class)->except(['show']);
