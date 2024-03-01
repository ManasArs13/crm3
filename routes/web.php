<?php

use App\Http\Controllers\ContactAmoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidualController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportTypeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-2', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-2');
    Route::get('/dashboard-3', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-3');
    
    Route::get('/fetch-orders', [DashboardController::class, 'fetchOrders'])->name('filter.orders');
    Route::get('/month-orders', [DashboardController::class, 'getOrderMonth'])->name('month.orders');
    Route::get('/map_data', [DashboardController::class, 'getOrderDataForMap'])->name('map.data');


    Route::resources([
        'order' => OrderController::class,
        'shipment' => ShipmentController::class,
        'product' => ProductController::class,
        'transport' => TransportController::class,
        'transportType' => TransportTypeController::class,
        'contactAmo' => ContactAmoController::class,
        'contact' => ContactController::class,
    ]);

    // Фильтры
    Route::get('/orders/filter', [OrderController::class, 'filter'])->name('order.filter');
    Route::get('/shipments/filter', [ShipmentController::class, 'filter'])->name('shipment.filter');
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('product.filter');
    Route::get('/transports/filter', [TransportController::class, 'filter'])->name('transport.filter');
    Route::get('/transportTypes/filter', [TransportTypeController::class, 'filter'])->name('transportType.filter');
    Route::get('/contactAmos/filter', [ContactAmoController::class, 'filter'])->name('contactAmo.filter');
    Route::get('/contacts/filter', [ContactController::class, 'filter'])->name('contact.filter');

    // Остатки
    Route::get('/residuals', [ResidualController::class, 'index'])->name('residual.index');
    Route::get('/residuals/blocks_materials', [ResidualController::class, 'blocksMaterials'])->name('residual.blocksMaterials');
    Route::get('/residuals/blocks_categories', [ResidualController::class, 'blocksCategories'])->name('residual.blocksCategories');
    Route::get('/residuals/blocks_products', [ResidualController::class, 'blocksProducts'])->name('residual.blocksProducts');
    Route::get('/residuals/concretes_materials', [ResidualController::class, 'concretesMaterials'])->name('residual.concretesMaterials');


    // Приёмки
    Route::resource('supplies', SupplyController::class)->only([
        'index', 'show'
    ]);
    Route::get('/supply/products', [SupplyController::class, 'products'])->name('supplies.products');




});

require __DIR__.'/auth.php';
