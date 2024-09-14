<?php

use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactAmoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Goods\IncomingController;
use App\Http\Controllers\Goods\OutgoingController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Amo\AmoOrderController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\OrderPositionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Production\ProcessingController;
use App\Http\Controllers\Production\TechChartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\CounterpartyController;
use App\Http\Controllers\Report\TransportController as ReportTransportController;
use App\Http\Controllers\Report\TransporterController;
use App\Http\Controllers\Report\TransporterFeeController;
use App\Http\Controllers\ResidualController;
use App\Http\Controllers\ShipingPriceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentProductController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportTypeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;



//Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
//Route::get('/blocks_materials', [WelcomeController::class, 'blocksMaterials'])->name('welcome.blocksMaterials');
//Route::get('/blocks_categories', [WelcomeController::class, 'blocksCategories'])->name('welcome.blocksCategories');
//Route::get('/blocks_products', [WelcomeController::class, 'blocksProducts'])->name('welcome.blocksProducts');
//Route::get('/concretes_materials', [WelcomeController::class, 'concretesMaterials'])->name('welcome.concretesMaterials');
//Route::get('/paint', [WelcomeController::class, 'paint'])->name('welcome.paint');
//Route::get('/processing', [WelcomeController::class, 'processing'])->name('welcome.processing');




Route::middleware(['auth', 'verified', 'role:operator'])->group(function () {
    // Operator windows
    Route::get('/operator/orders', [OperatorController::class, 'orders'])->name('operator.orders');
    Route::get('/operator/shipments', [OperatorController::class, 'shipments'])->name('operator.shipments');
});

Route::middleware(['auth', 'verified', 'role:admin|manager'])->group(function () {
    // Moy Sklad
    Route::resources([
        'manager' => ManagerController::class
    ]);
    // Менеджеры
    Route::get('/manager_block', [ManagerController::class, 'index_block'])->name('manager.index.block');
    Route::get('/manager_concrete', [ManagerController::class, 'index_concrete'])->name('manager.index.concrete');

    // Сводка
    Route::name('report.')->group(function () {

        // Контрагенты
        Route::get('/report/counterparty', [CounterpartyController::class, 'index'])->name('counteparty');
        Route::get('/report/counterparty_block', [CounterpartyController::class, 'block'])->name('counteparty.block');
        Route::get('/report/counterparty_concrete', [CounterpartyController::class, 'concrete'])->name('counteparty.concrete');

    });
});

Route::middleware(['auth', 'verified', 'role:admin|manager|dispatcher'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-2', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-2');
    Route::get('/dashboard-3', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-3');

    // Moy Sklad
    Route::resources([
        'order' => OrderController::class,
        'shipment' => ShipmentController::class,
    ]);

    // Остатки
//    Route::get('/residuals', [ResidualController::class, 'index'])->name('residual.index');
    Route::get('/residuals/blocks_materials', [ResidualController::class, 'blocksMaterials'])->name('residual.blocksMaterials');
    Route::get('/residuals/blocks_categories', [ResidualController::class, 'blocksCategories'])->name('residual.blocksCategories');
    Route::get('/residuals/blocks_products', [ResidualController::class, 'blocksProducts'])->name('residual.blocksProducts');
    Route::get('/residuals/concretes_materials', [ResidualController::class, 'concretesMaterials'])->name('residual.concretesMaterials');
    Route::get('/residuals/paint', [ResidualController::class, 'paint'])->name('residual.paint');
    Route::get('/residuals/processing', [ResidualController::class, 'processing'])->name('residual.processing');

    // Калькулятор
    Route::get('/calculator', [CalculatorController::class, 'block'])->name('calculator.block');

    // Должники
    Route::get('/shipments/debtors', [DebtorController::class, 'index'])->name('debtors');

    // Сводка
    Route::name('report.')->group(function () {

        // Транспорт
        Route::get('/report/transport', [ReportTransportController::class, 'index'])->name('transport');
        Route::get('/report/transport_block', [ReportTransportController::class, 'block'])->name('transport.block');
        Route::get('/report/transport_concrete', [ReportTransportController::class, 'concrete'])->name('transport.concrete');


        // Оплата
        Route::get('/report/transporter_fee', [TransporterFeeController::class, 'index'])->name('transporter_fee');
    });
});

Route::middleware(['auth', 'verified', 'role:admin|manager|dispatcher|carrier'])->group(function () {
    Route::name('report.')->group(function () {
        // Перевозчик
        Route::get('/report/transporter', [TransporterController::class, 'index'])->name('transporter');
        Route::get('/report/transporter_block', [TransporterController::class, 'block'])->name('transporter.block');
        Route::get('/report/transporter_concrete', [TransporterController::class, 'concrete'])->name('transporter.concrete');
    });
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'users'])->name('all');
        Route::get('/{role}', [UsersController::class, 'users'])->where('role', 'operator|manager|dispatcher|carrier')->name('roles');


        Route::resource('managment', UsersController::class)->only([
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ]);
    });

    Route::get('get-orders/{date}', [DashboardController::class, 'getOrders'])->name('get.orders');
    Route::get('/map_data', [DashboardController::class, 'getOrderDataForMap'])->name('map.data');


    // Moy Sklad
    Route::resources([
        'product' => ProductController::class,
        'transport' => TransportController::class,
        'transportType' => TransportTypeController::class,
        'contactAmo' => ContactAmoController::class,
        'contact' => ContactController::class,
        'delivery' => DeliveryController::class,
        'option' => OptionController::class,
        'shiping_price' => ShipingPriceController::class,
        'category' => CategoryController::class,
        'order_positions' => OrderPositionController::class,
        'shipment_products' => ShipmentProductController::class,
        'supply'    => SupplyController::class,
    ]);

    // Amo CRM
    Route::resources([
        'amo-order' => AmoOrderController::class,
    ]);

    // Amo
    //Route::r('/orders_amo', [AmoOrderController::class, 'index'])->name('amo.order.index');




    //Доп
    Route::post('shipments/createWithOrder', [ShipmentController::class, 'createWithOrder'])->name('shipment.createWithOrder');
    //Route::get('/supplies/products', [SupplyController::class, 'products'])->name('supplies.products');

    // Фильтры
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('product.filter');
    Route::get('/transportTypes/filter', [TransportTypeController::class, 'filter'])->name('transportType.filter');
    Route::get('/contactAmos/filter', [ContactAmoController::class, 'filter'])->name('contactAmo.filter');
    Route::get('/contacts/filter', [ContactController::class, 'filter'])->name('contact.filter');
    Route::get('/deliveries/filter', [DeliveryController::class, 'filter'])->name('delivery.filter');
    Route::get('/options/filter', [OptionController::class, 'filter'])->name('option.filter');
    Route::get('/shiping_prices/filter', [ShipingPriceController::class, 'filter'])->name('shiping_price.filter');
    Route::get('/categories/filter', [CategoryController::class, 'filter'])->name('category.filter');
    Route::get('/orderpositions/filter', [OrderPositionController::class, 'filter'])->name('orderposition.filter');
    Route::get('/shipmentproducts/filter', [ShipmentProductController::class, 'filter'])->name('shipmentproduct.filter');



    // Приёмки
    // Route::resource('supplies', SupplyController::class)->only([
    //     'index', 'show'
    // ]);


    // Приход
    Route::resource('incomings', IncomingController::class)->only([
        'index',
        'show',
        'store',
        'create',
        'update',
        'destroy'
    ]);
    Route::get('/incoming/products', [IncomingController::class, 'products'])->name('incomings.products');

    // Расход
    Route::resource('outgoings', OutgoingController::class)->only([
        'index',
        'show',
        'store',
        'create',
        'update',
        'destroy'
    ]);
    Route::get('/outgoing/products', [OutgoingController::class, 'products'])->name('outgoings.products');

    // Техкарты
    Route::resource('techcharts', TechChartController::class)->only([
        'index',
        'show'
    ]);
    Route::get('/techchart/products', [TechChartController::class, 'products'])->name('techcharts.products');
    Route::get('/techchart/materials', [TechChartController::class, 'materials'])->name('techcharts.materials');

    // Техоперации
    Route::resource('processings', ProcessingController::class)->only([
        'index',
        'show'
    ]);
    Route::get('/processing/products', [ProcessingController::class, 'products'])->name('processings.products');
    Route::get('/processing/materials', [ProcessingController::class, 'materials'])->name('processings.materials');

    Route::get('/summary', [SummaryController::class, 'index'])->name('summary.index');
});



require __DIR__ . '/auth.php';
