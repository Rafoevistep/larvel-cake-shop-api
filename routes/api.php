<?php

use App\Http\Controllers\Api\Admin\AboutUsPageController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\User\CartController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
],function ($router) {
    Route::controller(AuthController::class)->group(function() {
        Route::post('/login','login');
        Route::post('/register','register');
        Route::post('/logout','logout')->middleware('auth:sanctum');
        Route::get('/user','user')->middleware('auth:sanctum');
    });
    Route::controller(CartController::class)->group(function() {
        Route::post('/cart/{product}','store');
        Route::get('/cart','index');
        Route::delete('/cart/{product}', 'destroy');
    });
});

//--------For users Not Singn in------
Route::controller(ProductController::class)->group(function() {
    Route::get('/products', 'index');
    Route::get('/products/{product}', 'show');
    Route::get('search/{name}','search');
});

Route::controller(CategoryController::class)->group(function(){
    Route::get('/categoty', 'index');
    Route::get('/categoty/{categoty}', 'show');
});

Route::get('/about',[AboutUsPageController::class, 'index']);

//----------

Route::post('/profile/change-password',[ProfileController::class,'change_password'])->middleware('auth:sanctum');
Route::post('/profile/update-profile',[ProfileController::class,'update_profile'])->middleware('auth:sanctum');

//Route Vor Admin Minddlware
Route::group([
    'middleware' => 'admin',
    'prefix' => 'admin'
],function () {
    Route::controller(UserController::class)->group(function(){
        Route::get('/user', 'index');
        Route::put('/update-profile/{user}','update_profile');
        Route::get('/user/{user}','show');
    });

    Route::controller(CategoryController::class)->group(function(){
        Route::get('/categoty', 'index');
        Route::get('/categoty/{categoty}', 'show');
        Route::post('/categoty','store');
        Route::put('/categoty/{categoty}', 'update');
        Route::delete('/categoty/{categoty}', 'destroy');
    });

    Route::controller(ProductController::class)->group(function() {
        Route::get('/products', 'index');
        Route::get('/products/{product}', 'show');
        Route::post('/products','store');
        Route::put('/products/{product}', 'update');
        Route::delete('/products/{product}', 'destroy');
    });

    Route::controller(AboutUsPageController::class)->group(function(){
        Route::get('/about', 'index');
        Route::post('/about','store');
        Route::delete('/about/{about}', 'destroy');
    });

});
