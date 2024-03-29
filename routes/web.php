<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactAmoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Production\ProcessingController;
use App\Http\Controllers\Production\TechChartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResidualController;
use App\Http\Controllers\ShipingPriceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportTypeController;
use App\Http\Controllers\WelcomeController;
use App\Models\Contact;
use Illuminate\Support\Facades\Route;



Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
Route::get('/blocks_materials', [WelcomeController::class, 'blocksMaterials'])->name('welcome.blocksMaterials');
Route::get('/blocks_categories', [WelcomeController::class, 'blocksCategories'])->name('welcome.blocksCategories');
Route::get('/blocks_products', [WelcomeController::class, 'blocksProducts'])->name('welcome.blocksProducts');
Route::get('/concretes_materials', [WelcomeController::class, 'concretesMaterials'])->name('welcome.concretesMaterials');
Route::get('/paint', [WelcomeController::class, 'paint'])->name('welcome.paint');
Route::get('/processing', [WelcomeController::class, 'processing'])->name('welcome.processing');
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
        'delivery' => DeliveryController::class,
        'option' => OptionController::class,
        'shiping_price' => ShipingPriceController::class,
        'category' => CategoryController::class,
    ]);

    // Фильтры
    Route::get('/orders/filter', [OrderController::class, 'filter'])->name('order.filter');
    Route::get('/shipments/filter', [ShipmentController::class, 'filter'])->name('shipment.filter');
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('product.filter');
    Route::get('/transports/filter', [TransportController::class, 'filter'])->name('transport.filter');
    Route::get('/transportTypes/filter', [TransportTypeController::class, 'filter'])->name('transportType.filter');
    Route::get('/contactAmos/filter', [ContactAmoController::class, 'filter'])->name('contactAmo.filter');
    Route::get('/contacts/filter', [ContactController::class, 'filter'])->name('contact.filter');
    Route::get('/deliveries/filter', [DeliveryController::class, 'filter'])->name('delivery.filter');
    Route::get('/options/filter', [OptionController::class, 'filter'])->name('option.filter');
    Route::get('/shiping_prices/filter', [ShipingPriceController::class, 'filter'])->name('shiping_price.filter');
    Route::get('/categories/filter', [CategoryController::class, 'filter'])->name('category.filter');

    // Остатки
    Route::get('/residuals', [ResidualController::class, 'index'])->name('residual.index');
    Route::get('/residuals/blocks_materials', [ResidualController::class, 'blocksMaterials'])->name('residual.blocksMaterials');
    Route::get('/residuals/blocks_categories', [ResidualController::class, 'blocksCategories'])->name('residual.blocksCategories');
    Route::get('/residuals/blocks_products', [ResidualController::class, 'blocksProducts'])->name('residual.blocksProducts');
    Route::get('/residuals/concretes_materials', [ResidualController::class, 'concretesMaterials'])->name('residual.concretesMaterials');
    Route::get('/residuals/paint', [ResidualController::class, 'paint'])->name('residual.paint');
    Route::get('/residuals/processing', [ResidualController::class, 'processing'])->name('residual.processing');

    // Приёмки
    Route::resource('supplies', SupplyController::class)->only([
        'index', 'show'
    ]);
    Route::get('/supply/products', [SupplyController::class, 'products'])->name('supplies.products');

    // Техкарты
    Route::resource('techcharts', TechChartController::class)->only([
        'index', 'show'
    ]);
    Route::get('/techchart/products', [TechChartController::class, 'products'])->name('techcharts.products');
    Route::get('/techchart/materials', [TechChartController::class, 'materials'])->name('techcharts.materials');

    // Техоперации
    Route::resource('processings', ProcessingController::class)->only([
        'index', 'show'
    ]);
    Route::get('/processing/products', [ProcessingController::class, 'products'])->name('processings.products');
    Route::get('/processing/materials', [ProcessingController::class, 'materials'])->name('processings.materials');
});

require __DIR__ . '/auth.php';
