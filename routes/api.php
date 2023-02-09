<?php


use Illuminate\Support\Facades\Route;
use Wepa\Core\Http\Controllers\Api\FileManagerController;
use Wepa\Core\Http\Controllers\Api\FolderController;
use Wepa\Core\Http\Controllers\Api\MenuController;
use Wepa\Core\Http\Controllers\Api\SeoController;


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
Route::prefix('api')->middleware(['auth:sanctum', 'api'])->group(function() {
	Route::prefix('menu')->group(function(){
		Route::get('/{app}', [MenuController::class, 'getMenu'])->name('api.menu.index');
	});
	Route::prefix('seo')->group(function(){
		Route::get('slug/{text}/{locale?}', [SeoController::class, 'slug'])->name('api.seo.slug');
	});
	Route::prefix('filemanager')->group(function(){
		Route::get('files/{parentId?}', [FileManagerController::class, 'index'])->name('api.filenamager.index');
		Route::post('file/{parentId?}', [FileManagerController::class, 'store'])->name('api.filenamager.file.store');
		Route::put('file/{file}/{parentId?}', [FileManagerController::class, 'update'])->name('api.filenamager.file.update');
		Route::delete('file/{file}/{parentId?}', [FileManagerController::class, 'destroy'])->name('api.filenamager.file.destroy');
		Route::get('file/mime-types', [FileManagerController::class, 'mimeTypes'])->name('api.filenamager.mime_types');
		
		Route::post('folder/{parentId?}', [FileManagerController::class, 'folderStore'])->name('api.filenamager.folder.store');
		Route::put('folder/{file}/{parentId?}', [FileManagerController::class, 'folderUpdate'])->name('api.filenamager.folder.update');
	});
});
