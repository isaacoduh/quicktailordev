<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('API')->group(function(){
    Route::prefix('auth')->group(function(){
        Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register'])->name('signup');
        Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login'])->name('signin');
    });

    Route::get('categories.all', [App\Http\Controllers\API\CategoriesController::class, 'index'])->name('getcategories');
    Route::get('catalog/all', [App\Http\Controllers\API\CatalogController::class,'index'])->name('getcatalog');
    Route::post('createorder', [App\Http\Controllers\API\OrderController::class,'create'])->name('createorder');


    Route::prefix('orders')->group(function(){
        Route::get('/all',[App\Http\Controllers\API\OrderController::class, 'index'])->name('getorders');
    });

    Route::group(['middleware' => ['jwt.auth', 'admin']], function(){
        Route::prefix('users')->group(function(){
            Route::get('/all', [App\Http\Controllers\API\AuthController::class, 'getusers'])->name('getusers');
        });

        Route::prefix('categories')->group(function(){
            Route::post('/add', [App\Http\Controllers\API\CategoriesController::class, 'create'])->name('addcategory');
            Route::post('/{id}/update', [App\Http\Controllers\API\CategoriesController::class, 'update'])->name('updatecategory');
            Route::delete('/{id}/delete', [App\Http\Controllers\API\CategoriesController::class, 'destroy'])->name('deletecategory');
        });

        Route::prefix('catalog')->group(function(){
            Route::post('/add', [App\Http\Controllers\API\CatalogController::class,'create'])->name('addcatalog');
            Route::post('/{id}/update', [App\Http\Controllers\API\CatalogController::class,'update'])->name('updatecatalog');
            Route::post('/{id}/delete', [App\Http\Controllers\API\CatalogController::class,'destroy'])->name('deletecatalog');
        });
    });

});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
