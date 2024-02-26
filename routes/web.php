<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Models\Shipment;
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
    ]);

    Route::get('/orders/filter', [OrderController::class, 'filter'])->name('order.filter');
    Route::get('/shipments/filter', [ShipmentController::class, 'filter'])->name('shipment.filter');




});

require __DIR__.'/auth.php';
