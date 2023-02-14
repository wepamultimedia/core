<?php


use Illuminate\Support\Facades\Route;
use Wepa\Core\Http\Controllers\Api\V1\FileManagerController;
use Wepa\Core\Http\Controllers\Api\V1\MenuController;
use Wepa\Core\Http\Controllers\Api\V1\SeoController;
use Wepa\Core\Http\Controllers\Api\V1\SiteController;
use Wepa\Core\Http\Controllers\Api\V1\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('api/v1')->middleware(['api'])->group(function() {
	Route::post('/login', [UserController::class, 'login'])->name('api.v1.user.login');
	Route::get('site', [SiteController::class, 'site'])->name('api.v1.site');
});

Route::prefix('api/v1')->middleware(['web', 'auth:sanctum'])->group(function() {
	Route::get('menu/{app}', [MenuController::class, 'getMenu'])->name('api.v1.menu.index');
	
	// Deprected
	Route::prefix('seo')->group(function(){
		Route::get('slug/{text}/{locale?}', [SeoController::class, 'slug'])->name('api.v1.seo.slug');
	});
	
	Route::prefix('filemanager')->group(function(){
		Route::get('files/{parentId?}', [FileManagerController::class, 'index'])->name('api.v1.filenamager.index');
		Route::post('file/{parentId?}', [FileManagerController::class, 'store'])->name('api.v1.filenamager.file.store');
		Route::put('file/{file}/{parentId?}', [FileManagerController::class, 'update'])->name('api.v1.filenamager.file.update');
		Route::delete('file/{file}/{parentId?}', [FileManagerController::class, 'destroy'])->name('api.v1.filenamager.file.destroy');
		Route::get('file/mime-types', [FileManagerController::class, 'mimeTypes'])->name('api.v1.filenamager.mime_types');
		
		Route::post('folder/{parentId?}', [FileManagerController::class, 'folderStore'])->name('api.v1.filenamager.folder.store');
		Route::put('folder/{file}/{parentId?}', [FileManagerController::class, 'folderUpdate'])->name('api.v1.filenamager.folder.update');
	});
});
