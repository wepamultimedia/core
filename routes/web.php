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
use Wepa\Core\Http\Controllers\Frontend\LoginController;
use Wepa\Core\Http\Controllers\Mixed\ForgotPasswordController;
use Wepa\Core\Http\Controllers\Mixed\RegisterController;
use Wepa\Core\Http\Controllers\Mixed\ResetPasswordController;


Route::middleware('web')->group(function() {
	Route::get('login', [LoginController::class, 'create'])->name('login');
	Route::post('login', [LoginController::class, 'store'])->name('login');
	Route::get('register', [RegisterController::class, 'create'])->name('register');
	Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
	Route::get('reset-password/{email}/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
});

Route::middleware(['web', 'auth:sanctum'])->group(function(){
	Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require 'admin.php';
