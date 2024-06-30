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

Route::get('/shipments/get/month_category', [App\Http\Controllers\Api\Site\ShipmentController::class, 'getShipmentsByMonthAndCategory'])->name('api.get.month.category');
Route::post('/order_ms/create', [App\Http\Controllers\Api\Ms\OrderController::class, 'setOrderFromCalculator'])->name("api.post.order");
Route::post("/shipping_price/get", [App\Http\Controllers\Api\Site\ShipingPriceController::class,"getPrice"])->name("api.get.shipping_price");
Route::get("/deliveries/get/name", [App\Http\Controllers\Api\Site\DeliveryController::class,"getByName"])->name("api.get.delivery");
Route::get("/contacts/get/name", [App\Http\Controllers\Api\Site\ContactController::class,"getByName"])->name("api.get.contact.name");
Route::get("/contacts/get/phone", [App\Http\Controllers\Api\Site\ContactController::class,"getByPhone"])->name("api.get.contact.phone");
Route::get("/states/get/name", [App\Http\Controllers\Api\Site\StatusMsController::class,"getByName"])->name("api.get.state.name");
