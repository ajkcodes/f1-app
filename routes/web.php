<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\RaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('races', [RaceController::class, 'index'])->name('races.index');
Route::get('drivers', [DriverController::class, 'index'])->name('drivers.index');
