<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Wepa\Core\Http\Controllers\Frontend\InertiaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware(['web', 'core.locale'])->group(function () {

    // Multilanguage
    if(count(config('core.locales')) > 1){
        Route::get('{locale}/{slug?}', [InertiaController::class, 'slugRedirect'])->where('slug', '[a-z]{2}');
        Route::get('{locale}/{prefix}/{slug?}', [InertiaController::class, 'slugRedirect'])->where('slug', '[a-z]{2}');
    } else {
        Route::get('{prefix}/{slug?}', [InertiaController::class, 'slugRedirect'])->where('slug', '[a-z]{2}');
        Route::get('{slug?}', [InertiaController::class, 'slugRedirect']);

    }
});
