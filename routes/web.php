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
use Wepa\Core\Http\Controllers\LocaleController;

require 'admin.php';

Route::middleware(['web', 'auth:sanctum', 'core.https'])->group(function () {
});

Route::middleware(['web', 'core.https'])->group(function () {
    Route::get('/locale/{locale}', [LocaleController::class, 'switchLocale'])->name('locale');
});
