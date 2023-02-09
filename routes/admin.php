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
use Wepa\Core\Http\Controllers\Backend\FileManagerController;
use Wepa\Core\Http\Controllers\Backend\PermissionController;
use Wepa\Core\Http\Controllers\Backend\RoleController;
use Wepa\Core\Http\Controllers\Backend\SeoController;
use Wepa\Core\Http\Controllers\Backend\SiteController;
use Wepa\Core\Http\Controllers\Backend\TranslationController;
use Wepa\Core\Http\Controllers\Backend\UserController;
use Wepa\Core\Http\Controllers\Backend\UserProfileController;


Route::prefix('admin')->middleware(['web', 'auth:sanctum'])->group(function() {
	
	Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
	
	Route::get('user/profile', [UserProfileController::class, 'show'])->name('admin.user.profile');
	Route::resource('users', UserController::class)->names('admin.users');
	Route::resource('roles', RoleController::class)->names('admin.roles');
	Route::resource('permissions', PermissionController::class)->names('admin.permissions');
	Route::post('site/icons/generate', [SiteController::class, 'generateIcons'])->name('admin.site.icons.generate');
	Route::get('site/edit', [SiteController::class, 'edit'])->name('admin.site.edit');
	Route::put('site/update', [SiteController::class, 'update'])->name('admin.site.update');
//	Route::put('site/update', [SiteController::class, 'update'])->name('admin.site.update');
	Route::resource('seo', SeoController::class)->names('admin.seo');
	
	Route::resource('translations', TranslationController::class)->names('admin.translations');
	
	Route::get('/files', [FileManagerController::class, 'index'])->name('admin.files.index');
	
});
