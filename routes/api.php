<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/orders', [App\Http\Controllers\OrderController::class, 'get_api'])->name('api.get.order');
Route::get('/contacts', [ContactController::class, 'get_api'])->name('api.get.contact');
Route::get('/products', [ProductController::class, 'get_api'])->name('api.get.product');

Route::post('/order_ms/create', [App\Http\Controllers\Api\Ms\OrderController::class, 'setOrderFromCalculator'])->name("api.post.order");
Route::post("/shipping_price/get", [App\Http\Controllers\Api\Site\ShipingPriceController::class,"getPrice"])->name("api.get.shipping_price");
