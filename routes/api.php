<?php

use App\Http\Controllers\Api\Ms\OrderController as MsOrderController;
use App\Http\Controllers\Api\Site\OrderController as SiteOrderController;
use App\Http\Controllers\OrderController as OrdController;
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

Route::get('/orders', [OrdController::class, 'get_api'])->name('api.get.order');
Route::get('get/orders/', [OrderController::class, 'order_get_calculator'])->name('order.get.calculator');
Route::get('/contacts', [ContactController::class, 'get_api'])->name('api.get.contact');
Route::get('/products', [ProductController::class, 'get_api'])->name('api.get.product');

Route::get('/shipments/get/month_category', [App\Http\Controllers\Api\Site\ShipmentController::class, 'getShipmentsByMonthAndCategory'])->name('api.get.month.category');
Route::post('/order_ms/create', [MsOrderController::class, 'setOrderFromCalculator'])->name("api.post.ms.order");
Route::post('/order_site/create', [SiteOrderController::class, 'setOrderFromCalculator'])->name("api.post.site.order");
Route::get("/shipping_price/get", [App\Http\Controllers\Api\Site\ShipingPriceController::class,"getPrice"])->name("api.get.shipping_price");
Route::get("/deliveries/get/name", [App\Http\Controllers\Api\Site\DeliveryController::class,"getByName"])->name("api.get.delivery");
Route::get("/contacts/get/name", [App\Http\Controllers\Api\Site\ContactController::class,"getByName"])->name("api.get.contact.name");
Route::get("/contacts/get/phone", [App\Http\Controllers\Api\Site\ContactController::class,"getByPhone"])->name("api.get.contact.phone");
Route::get("/states/get/name", [App\Http\Controllers\Api\Site\StatusMsController::class,"getByName"])->name("api.get.state.name");
