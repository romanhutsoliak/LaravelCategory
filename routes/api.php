<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\CategoryController;
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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
    
Route::group(['middleware' => ['auth:sanctum']], function () {
    
    // auth 
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // api
    Route::resource('/categories', CategoryController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('/products', ProductController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
});


