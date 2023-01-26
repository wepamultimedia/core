<?php

/*
|--------------------------------------------------------------------------
| Core Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for your package.
|
*/

use Illuminate\Support\Facades\Route;
use Wepa\Core\Http\Controllers\Frontend\DashboardController;
use Wepa\Core\Http\Controllers\Frontend\InertiaController;

require 'admin.php';

Route::middleware(['web', 'auth:sanctum'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware('web')->group(function () {
    Route::get('{slug}', [InertiaController::class, 'slugRedirect']);
});
