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
use Wepa\CoreClient\Http\Controllers\AuthController;


Route::middleware('web')->group(function(){
//	Route::get('login', [AuthController::class, 'login'])->name('login');
//	Route::post('login', [AuthController::class, 'authenticate'])->name('login');
//	Route::post('logout', [AuthController::class, 'logout'])->name('logout');
//	Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});
