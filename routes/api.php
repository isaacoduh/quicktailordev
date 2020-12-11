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
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
