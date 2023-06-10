<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\ProductController;
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


Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'getCurrnetUser']);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('orders', OrderController::class);
    Route::resource('orderDetails', OrderDetailsController::class);
    Route::patch('patch', [OrderDetailsController::class, 'update']);
    Route::post('orderDetails/update', [OrderDetailsController::class, 'updateOrderDetails']);
    Route::get('/categories', [CategoryController::class, 'index']);
});
