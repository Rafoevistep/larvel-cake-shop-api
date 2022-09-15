<?php

use App\Http\Controllers\Api\Admin\AboutUsPageController;
use App\Http\Controllers\Api\Admin\AvailableProductController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ContactPageController;
use App\Http\Controllers\Api\Admin\OrderAnalyticsController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\User\CartController;
use App\Http\Controllers\Api\User\OrderController;
use App\Http\Controllers\Api\User\EnquiryController;
use App\Http\Controllers\Api\User\NewsletterControoler;
use App\Http\Controllers\Api\User\StripePaymanetController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(CartController::class)->group(function () {
            Route::post('/cart/{product}', 'store');
            Route::get('/cart', 'index');
            Route::delete('/cart/{product}', 'destroy');
        });

        Route::post('enquary', [EnquiryController::class, 'store']);

        Route::controller(OrderController::class)->group(function () {
            Route::post('/checkout', 'store');
            Route::post('/checkout/{product}', 'storeSingle');
            Route::put('/myorder/cancel/{order}', 'cancel');
            Route::get('/myorder', 'myorder');
            Route::get('/myorder/order_export', 'get_orders_data');
        });
    });

    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::get('/logout', 'logout')->middleware('auth:sanctum');
        Route::get('/user', 'user')->middleware('auth:sanctum');
    });

    Route::post('/stripe/{product}', [StripePaymanetController::class, 'stripeBuyNow']);
    Route::post('/stripe', [StripePaymanetController::class, 'stripeChekoutCard']);
});

Route::post('/profile/change-password', [ProfileController::class, 'change_password'])->middleware('auth:sanctum');
Route::post('/profile/update-profile', [ProfileController::class, 'update_profile'])->middleware('auth:sanctum');


//--------For users Not Singn in-------------------

Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index');
    Route::get('/products/available', 'indexAvailableProduct');
    Route::get('/products/{product}', 'show');
    Route::get('/products/search/{name}', 'search');
});

Route::controller(CategoryController::class)->group(function () {
    Route::get('/categoty', 'index');
    Route::get('/categoty/{categoty}', 'show');
});

Route::get('/about', [AboutUsPageController::class, 'index']);
Route::get('/contact', [ContactPageController::class, 'index']);

Route::post('/subscripe', [NewsletterControoler::class, 'store']);


Route::get('/order/search/{order}', [OrderController::class, 'search']);

//------------------------------------


//Route Vor Admin Minddlware
Route::group([
    'middleware' => 'admin',
    'prefix' => 'admin'
], function () {
    Route::controller(UserController::class)->group(function () {
        Route::get('/user', 'index');
        Route::put('/update-profile/{user}', 'update_profile');
        Route::get('/user/{user}', 'show');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/categoty', 'index');
        Route::get('/categoty/{categoty}', 'show');
        Route::post('/categoty', 'store');
        Route::put('/categoty/{categoty}', 'update');
        Route::delete('/categoty/{categoty}', 'destroy');
    });

    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index');
        Route::get('/products/{product}', 'show');
        Route::post('/products-create', 'store');
        Route::put('/products/{product}', 'update');
        Route::delete('/products/{product}', 'destroy');
    });

    Route::controller(AboutUsPageController::class)->group(function () {
        Route::get('/about', 'index');
        Route::post('/about', 'store');
    });

    Route::controller(ContactPageController::class)->group(function () {
        Route::get('/contact', 'index');
        Route::post('/contact', 'store');
    });

    Route::get('/subscripe', [NewsletterControoler::class, 'index']);


    Route::get('enquary', [EnquiryController::class, 'index']);

    Route::controller(OrderController::class)->group(function () {
        Route::get('/checkout', 'index');
        Route::get('/checkout/{checkout}', 'show');
        Route::put('/checkout/{checkout}', 'update');
    });

    Route::controller(OrderAnalyticsController::class)->group(function () {
        Route::get('/order/alytics/total', 'total');
        Route::get('/order/alytics/notconfirmed', 'notConfirmed');
        Route::get('/order/alytics/cancelled', 'cancelled');
        Route::get('/order/alytics/completed', 'completed');
        Route::get('/order/alytics/prepeared', 'prepeared');
        Route::get('/order/alytics/pickup', 'pickup');
        Route::get('/order/alytics/deleveried', 'deleveried');
        Route::get('/order/alytics/sales', 'showSales');
    });
});
