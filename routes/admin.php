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
use Laravel\Fortify\Contracts\LoginViewResponse as FortifyViewResponse;
use Laravel\Fortify\Http\Responses\SimpleViewResponse;
use Wepa\Core\Http\Controllers\Backend\DashboardController;
use Wepa\Core\Http\Controllers\Backend\FileManagerController;
use Wepa\Core\Http\Controllers\Backend\LoginController;
use Wepa\Core\Http\Controllers\Backend\PermissionController;
use Wepa\Core\Http\Controllers\Backend\RoleController;
use Wepa\Core\Http\Controllers\Backend\SeoController;
use Wepa\Core\Http\Controllers\Backend\TranslationController;
use Wepa\Core\Http\Controllers\Backend\UserController;
use Wepa\Core\Http\Controllers\Backend\UserProfileController;


Route::prefix('admin')
	->middleware(['web', 'auth:sanctum', 'core.backend'])
	->group(function() {
		
		Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
		
		Route::get('user/profile', [UserProfileController::class, 'show'])->name('admin.user.profile');
		Route::resource('users', UserController::class)->names('admin.users');
		Route::resource('roles', RoleController::class)->names('admin.roles');
		Route::resource('permissions', PermissionController::class)->names('admin.permissions');
		Route::resource('seo', SeoController::class)->names('admin.seo');
		Route::get('seo/setup', [SeoController::class, 'setup'])->name('admin.seo.setup');
		Route::resource('translations', TranslationController::class)->names('admin.translations');
		
		Route::get('/files', [FileManagerController::class, 'index'])->name('admin.files.index');
		
	});
