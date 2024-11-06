<?php

use App\Http\Controllers\AmoContactsBanchController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactAmoController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CarrierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DebtorController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ErrorTypeController;
use App\Http\Controllers\Goods\IncomingController;
use App\Http\Controllers\Goods\OutgoingController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Amo\AmoOrderController;
use App\Http\Controllers\Amo\AmosContactController;
use App\Http\Controllers\Amo\CallController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\OrderPositionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Production\ProcessingController;
use App\Http\Controllers\Production\TechChartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\DaysController;
use App\Http\Controllers\Report\DeviationController;
use App\Http\Controllers\Report\CashController;
use App\Http\Controllers\Report\CounterpartyController;
use App\Http\Controllers\Report\ReportDeliveryController;
use App\Http\Controllers\Report\TransportController as ReportTransportController;
use App\Http\Controllers\Report\TransporterController;
use App\Http\Controllers\Report\TransporterFeeController;
use App\Http\Controllers\ResidualController;
use App\Http\Controllers\ShipingPriceController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ShipmentProductController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplyPositionController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportTypeController;
//use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;



//Route::get('/', [WelcomeController::class, 'index'])->name('welcome.index');
//Route::get('/blocks_materials', [WelcomeController::class, 'blocksMaterials'])->name('welcome.blocksMaterials');
//Route::get('/blocks_categories', [WelcomeController::class, 'blocksCategories'])->name('welcome.blocksCategories');
//Route::get('/blocks_products', [WelcomeController::class, 'blocksProducts'])->name('welcome.blocksProducts');
//Route::get('/concretes_materials', [WelcomeController::class, 'concretesMaterials'])->name('welcome.concretesMaterials');
//Route::get('/paint', [WelcomeController::class, 'paint'])->name('welcome.paint');
//Route::get('/processing', [WelcomeController::class, 'processing'])->name('welcome.processing');


Route::middleware(['auth', 'verified'])->group(function () {
    // Operator windows
    Route::get('/operator/orders', [OperatorController::class, 'orders'])->name('operator.orders');
    Route::get('/operator/shipments', [OperatorController::class, 'shipments'])->name('operator.shipments');


    // Менеджеры
    Route::get('/manager_block', [ManagerController::class, 'index_block'])->name('manager.index.block');
    Route::get('/manager_concrete', [ManagerController::class, 'index_concrete'])->name('manager.index.concrete');
    Route::get('/manager-two', [ManagerController::class, 'managerTwo'])->name('manager.managerTwo');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-2', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-2');
    Route::get('/dashboard-3', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-3');

    // Финансы
    Route::get('/finance', [PaymentController::class, 'index'])->name('finance.index');
    Route::get('/finance/cashin', [PaymentController::class, 'cashin'])->name('finance.cashin');
    Route::get('/finance/cashout', [PaymentController::class, 'cashout'])->name('finance.cashout');
    Route::get('/finance/paymentin', [PaymentController::class, 'paymentin'])->name('finance.paymentin');
    Route::get('/finance/paymentout', [PaymentController::class, 'paymentout'])->name('finance.paymentout');

    // API for dashboard
    Route::get('get-orders/{date}', [DashboardController::class, 'getOrders'])->name('get.orders');
    Route::get('/map_data', [DashboardController::class, 'getOrderDataForMap'])->name('map.data');


    // Принт
    Route::group(['prefix' => 'print'], function () {
        Route::post('/order', [OrderController::class, 'print'])->name('print.order');
        Route::post('/shipment', [ShipmentController::class, 'print'])->name('print.shipment');
    });

    // Остатки
    //    Route::get('/residuals', [ResidualController::class, 'index'])->name('residual.index');
    Route::get('/residuals/blocks_materials', [ResidualController::class, 'blocksMaterials'])->name('residual.blocksMaterials');
    Route::get('/residuals/blocks_categories', [ResidualController::class, 'blocksCategories'])->name('residual.blocksCategories');
    Route::get('/residuals/blocks_products', [ResidualController::class, 'blocksProducts'])->name('residual.blocksProducts');
    Route::get('/residuals/concretes_materials', [ResidualController::class, 'concretesMaterials'])->name('residual.concretesMaterials');
//    Route::get('/residuals/paint', [ResidualController::class, 'paint'])->name('residual.paint');
//    Route::get('/residuals/processing', [ResidualController::class, 'processing'])->name('residual.processing');

    // Калькулятор
    Route::get('/calculator', [CalculatorController::class, 'block'])->name('calculator.block');

    // Должники
    Route::get('/shipments/debtors', [DebtorController::class, 'index'])->name('debtors');
    Route::get('/shipments/debtors/{debtor}', [DebtorController::class, 'index'])->where('debtor', 'norm|fizik|problem|urik')->name('debtors.tab');

    // Сводка
    Route::name('report.')->group(function () {

        // Транспорт
        Route::get('/report/transport', [ReportTransportController::class, 'index'])->name('transport');
        Route::get('/report/transport_block', [ReportTransportController::class, 'block'])->name('transport.block');
        Route::get('/report/transport_concrete', [ReportTransportController::class, 'concrete'])->name('transport.concrete');

        // Перевозчики
        Route::get('/report/transporter_fee', [TransporterFeeController::class, 'index'])->name('transporter_fee');

        // Перевозчик
        Route::get('/report/transporter', [TransporterController::class, 'index'])->name('transporter');
        Route::get('/report/transporter_block', [TransporterController::class, 'block'])->name('transporter.block');
        Route::get('/report/transporter_concrete', [TransporterController::class, 'concrete'])->name('transporter.concrete');

        // Сводка по дням
        Route::get('/report/days', [DaysController::class, 'index'])->name('days');

        // Отклонения от цен
        Route::get('/report/deviations', [DeviationController::class, 'index'])->name('deviations');

        // Доставки
        Route::get('/report/delivery', [ReportDeliveryController::class, 'index'])->name('delivery');
        Route::get('/report/delivery_category', [ReportDeliveryController::class, 'category'])->name('delivery.category');
//        Route::get('/report/delivery_object', [ReportDeliveryController::class, 'object'])->name('delivery.object');

        // Контрагенты
        Route::get('/report/counterparty', [CounterpartyController::class, 'index'])->name('counteparty');
        Route::get('/report/counterparty_block', [CounterpartyController::class, 'block'])->name('counteparty.block');
        Route::get('/report/counterparty_concrete', [CounterpartyController::class, 'concrete'])->name('counteparty.concrete');

        // Route::get('/report/cash', [CashController::class, 'index'])->name('cash.index');
        // Route::get('/report/cash/in', [CashController::class, 'cashin'])->name('cash.cashin');
        // Route::get('/report/cash/out', [CashController::class, 'cashout'])->name('cash.cashout');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Moy Sklad
    Route::resources([
        'amo-order' => AmoOrderController::class,
        'product' => ProductController::class,
        'transport' => TransportController::class,
        'transportType' => TransportTypeController::class,
        'contactAmo' => ContactAmoController::class,
        'contact' => ContactController::class,
        'delivery' => DeliveryController::class,
        'shiping_price' => ShipingPriceController::class,
        'category' => CategoryController::class,
        'order_positions' => OrderPositionController::class,
        'shipment_products' => ShipmentProductController::class,
        'supply' => SupplyController::class,
        'supply_positions' => SupplyPositionController::class,
        'errors' => ErrorController::class,
        'errorTypes' => ErrorTypeController::class,
        'order' => OrderController::class,
        'shipment' => ShipmentController::class,
        'manager' => ManagerController::class,
        'option' => OptionController::class
    ]);

    // звонки и беседы
    Route::get('calls', [CallController::class, 'index'])->name('calls');
    Route::get('conversations', [CallController::class, 'conversations'])->name('conversations');

    Route::get('bunch_of_contacts', [AmosContactController::class, 'index'])->name('bunch_of_contacts');
    Route::get('double_of_orders', [AmoOrderController::class, 'doubleOrders'])->name('double_of_orders');


    Route::prefix('transports')->name('transport.')->group(function () {
        Route::resource('shift', ShiftController::class)->only(['index', 'update']);
        Route::get('/{shift}', [ShiftController::class, 'index'])->where('shift', 'onshift|offshift')->name('shifts');
    });

    //Доп
    Route::get('shipments/createFromOrder/{orderId}', [ShipmentController::class, 'createFromOrder'])->name('shipment.createFromOrder');
    Route::post('shipments/createWithOrder', [ShipmentController::class, 'createWithOrder'])->name('shipment.createWithOrder');

    // Фильтры
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('product.filter');
    Route::get('/transportTypes/filter', [TransportTypeController::class, 'filter'])->name('transportType.filter');
    Route::get('/contactAmos/filter', [ContactAmoController::class, 'filter'])->name('contactAmo.filter');
    Route::get('/contacts/filter', [ContactController::class, 'filter'])->name('contact.filter');
    Route::get('/deliveries/filter', [DeliveryController::class, 'filter'])->name('delivery.filter');
    Route::get('/shiping_prices/filter', [ShipingPriceController::class, 'filter'])->name('shiping_price.filter');
    Route::get('/categories/filter', [CategoryController::class, 'filter'])->name('category.filter');
    Route::get('/orderpositions/filter', [OrderPositionController::class, 'filter'])->name('orderposition.filter');

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
    Route::get('/summary/remains', [SummaryController::class, 'remains'])->name('summary.remains');


    // Permission
    Route::get('/permission', [UsersController::class, 'permission'])->name('permission');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'users'])->name('all');
        Route::get('/{role}', [UsersController::class, 'users'])->where('role', 'operator|manager|dispatcher|carrier|audit')->name('roles');


        Route::resource('managment', UsersController::class)->only([
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ]);
    });

    Route::get('/options/filter', [OptionController::class, 'filter'])->name('option.filter');
});


Route::get('/carrier/mypage', [CarrierController::class, 'index'])->name('carrier.index');


require __DIR__ . '/auth.php';
