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
use Wepa\Core\Http\Controllers\Backend\DashboardController;
use Wepa\Core\Http\Controllers\Backend\LoginController;
use Wepa\Core\Http\Controllers\Backend\PermissionController;
use Wepa\Core\Http\Controllers\Backend\RoleController;
use Wepa\Core\Http\Controllers\Backend\TranslationController;
use Wepa\Core\Http\Controllers\Backend\UserController;


Route::prefix('admin')->middleware('web')->group(function() {
	Route::get('login', [LoginController::class, 'create'])->name('admin.login');
	Route::post('login', [LoginController::class, 'store'])->name('admin.login');
});

Route::prefix('admin')->middleware(['web', 'auth:sanctum', 'core.backend'])->group(function() {
	Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
	
	Route::resource('users', UserController::class)->names('admin.users');
	Route::resource('roles', RoleController::class)->names('admin.roles');
	Route::resource('permissions', PermissionController::class)->names('admin.permissions');
	
	Route::get('/translations', [TranslationController::class, 'index'])->name('admin.translations');
});
