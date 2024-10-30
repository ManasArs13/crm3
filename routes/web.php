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
    Route::get('/operator/orders', [OperatorController::class, 'orders'])->name('operator.orders')->middleware('permission:operator_order');
    Route::get('/operator/shipments', [OperatorController::class, 'shipments'])->name('operator.shipments')->middleware('permission:operator_shipment');


    // Менеджеры
    Route::get('/manager_block', [ManagerController::class, 'index_block'])->name('manager.index.block')->middleware('permission:report_manager');
    Route::get('/manager_concrete', [ManagerController::class, 'index_concrete'])->name('manager.index.concrete')->middleware('permission:report_manager');
    Route::get('/manager-two', [ManagerController::class, 'managerTwo'])->name('manager.managerTwo')->middleware('permission:report_manager_two');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('permission:home');
    Route::get('/dashboard-2', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-2')->middleware('permission:home');
    Route::get('/dashboard-3', [DashboardController::class, 'buildingsMaterialDashboard'])->name('dashboard-3')->middleware('permission:home');

    // Финансы
    Route::get('/finance', [PaymentController::class, 'index'])->name('finance.index')->middleware('permission:payment');
    Route::get('/finance/cashin', [PaymentController::class, 'cashin'])->name('finance.cashin')->middleware('permission:payment_edit');
    Route::get('/finance/cashout', [PaymentController::class, 'cashout'])->name('finance.cashout')->middleware('permission:payment_edit');
    Route::get('/finance/paymentin', [PaymentController::class, 'paymentin'])->name('finance.paymentin')->middleware('permission:payment_edit');
    Route::get('/finance/paymentout', [PaymentController::class, 'paymentout'])->name('finance.paymentout')->middleware('permission:payment_edit');

    // API for dashboard
    Route::get('get-orders/{date}', [DashboardController::class, 'getOrders'])->name('get.orders')->middleware('permission:home');
    Route::get('/map_data', [DashboardController::class, 'getOrderDataForMap'])->name('map.data')->middleware('permission:home');


    // Принт
    Route::group(['prefix' => 'print'], function () {
        Route::post('/order', [OrderController::class, 'print'])->name('print.order')->middleware('permission:order');
        Route::post('/shipment', [ShipmentController::class, 'print'])->name('print.shipment')->middleware('permission:shipment');
    });

    // Остатки
    //    Route::get('/residuals', [ResidualController::class, 'index'])->name('residual.index');
    Route::get('/residuals/blocks_materials', [ResidualController::class, 'blocksMaterials'])->name('residual.blocksMaterials')->middleware('permission:residual');
    Route::get('/residuals/blocks_categories', [ResidualController::class, 'blocksCategories'])->name('residual.blocksCategories')->middleware('permission:residual');
    Route::get('/residuals/blocks_products', [ResidualController::class, 'blocksProducts'])->name('residual.blocksProducts');
    Route::get('/residuals/concretes_materials', [ResidualController::class, 'concretesMaterials'])->name('residual.concretesMaterials');
//    Route::get('/residuals/paint', [ResidualController::class, 'paint'])->name('residual.paint');
//    Route::get('/residuals/processing', [ResidualController::class, 'processing'])->name('residual.processing');

    // Калькулятор
    Route::get('/calculator', [CalculatorController::class, 'block'])->name('calculator.block')->middleware('permission:calculator');

    // Должники
    Route::get('/shipments/debtors', [DebtorController::class, 'index'])->name('debtors')->middleware('permission:debtor');
    Route::get('/shipments/debtors/{debtor}', [DebtorController::class, 'index'])->where('debtor', 'norm|fizik|problem|urik')->name('debtors.tab')->middleware('permission:debtor');

    // Сводка
    Route::name('report.')->group(function () {

        // Транспорт
        Route::get('/report/transport', [ReportTransportController::class, 'index'])->name('transport')->middleware('permission:report_transport');
        Route::get('/report/transport_block', [ReportTransportController::class, 'block'])->name('transport.block')->middleware('permission:report_transport');
        Route::get('/report/transport_concrete', [ReportTransportController::class, 'concrete'])->name('transport.concrete')->middleware('permission:report_transport');

        // Перевозчики
        Route::get('/report/transporter_fee', [TransporterFeeController::class, 'index'])->name('transporter_fee')->middleware('permission:transporter_fee');

        // Перевозчик
        Route::get('/report/transporter', [TransporterController::class, 'index'])->name('transporter')->middleware('permission:report_transporter');
        Route::get('/report/transporter_block', [TransporterController::class, 'block'])->name('transporter.block')->middleware('permission:report_transporter');
        Route::get('/report/transporter_concrete', [TransporterController::class, 'concrete'])->name('transporter.concrete')->middleware('permission:report_transporter');

        // Сводка по дням
        Route::get('/report/days', [DaysController::class, 'index'])->name('days')->middleware('permission:report_day');

        // Отклонения от цен
        Route::get('/report/deviations', [DeviationController::class, 'index'])->name('deviations')->middleware('permission:report_deviation');

        // Доставки
        Route::get('/report/delivery', [ReportDeliveryController::class, 'index'])->name('delivery')->middleware('permission:report_delivery');
        Route::get('/report/delivery_category', [ReportDeliveryController::class, 'category'])->name('delivery.category')->middleware('permission:report_delivery_category');

        // Контрагенты
        Route::get('/report/counterparty', [CounterpartyController::class, 'index'])->name('counteparty')->middleware('permission:report_counterparty');
        Route::get('/report/counterparty_block', [CounterpartyController::class, 'block'])->name('counteparty.block')->middleware('permission:report_counterparty');
        Route::get('/report/counterparty_concrete', [CounterpartyController::class, 'concrete'])->name('counteparty.concrete')->middleware('permission:report_counterparty');

        // Route::get('/report/cash', [CashController::class, 'index'])->name('cash.index');
        // Route::get('/report/cash/in', [CashController::class, 'cashin'])->name('cash.cashin');
        // Route::get('/report/cash/out', [CashController::class, 'cashout'])->name('cash.cashout');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Moy Sklad
    Route::resource('amo-order', AmoOrderController::class)->middleware('permission:amo_order');
    Route::resource('product', ProductController::class)->middleware('permission:product');
    Route::resource('transport', TransportController::class)->middleware('permission:transport');
    Route::resource('transportType', TransportTypeController::class)->middleware('permission:transport_type');
    Route::resource('contactAmo', ContactAmoController::class)->middleware('permission:amo_contact');
    Route::resource('contact', ContactController::class)->middleware('permission:contact');
    Route::resource('delivery', DeliveryController::class)->middleware('permission:delivery');
    Route::resource('shiping_price', ShipingPriceController::class)->middleware('permission:delivery_price');
    Route::resource('category', CategoryController::class)->middleware('permission:category_product');
    Route::resource('order_positions', OrderPositionController::class)->middleware('permission:order_position');
    Route::resource('shipment_products', ShipmentProductController::class)->middleware('permission:shipment_position');
    Route::resource('supply', SupplyController::class)->middleware('permission:supply');
    Route::resource('supply_positions', SupplyPositionController::class)->middleware('permission:supply_position');
    Route::resource('errors', ErrorController::class)->middleware('permission:error');
    Route::resource('errorTypes', ErrorTypeController::class)->middleware('permission:error_type');
    Route::resource('order', OrderController::class)->middleware('permission:order');
    Route::resource('shipment', ShipmentController::class)->middleware('permission:shipment');
    Route::resource('manager', ManagerController::class)->middleware('permission:report_manager');
    Route::resource('option', OptionController::class)->middleware('permission:option');

    // звонки и беседы
    Route::get('calls', [CallController::class, 'index'])->name('calls')->middleware('permission:call');
    Route::get('conversations', [CallController::class, 'conversations'])->name('conversations')->middleware('permission:conversation');

    Route::get('bunch_of_contacts', [AmosContactController::class, 'index'])->name('bunch_of_contacts')->middleware('permission:contact_link');
    Route::get('double_of_orders', [AmoOrderController::class, 'doubleOrders'])->name('double_of_orders')->middleware('permission:double_order');


    Route::prefix('transports')->name('transport.')->group(function () {
        Route::resource('shift', ShiftController::class)->only(['index', 'update'])->middleware('permission:shift');
        Route::get('/{shift}', [ShiftController::class, 'index'])->where('shift', 'onshift|offshift')->name('shifts')->middleware('permission:shift');
    });

    //Доп
    Route::get('shipments/createFromOrder/{orderId}', [ShipmentController::class, 'createFromOrder'])->name('shipment.createFromOrder')->middleware('permission:shipment_edit');
    Route::post('shipments/createWithOrder', [ShipmentController::class, 'createWithOrder'])->name('shipment.createWithOrder')->middleware('permission:shipment_edit');

    // Фильтры
    Route::get('/products/filter', [ProductController::class, 'filter'])->name('product.filter')->middleware('permission:product');
    Route::get('/transportTypes/filter', [TransportTypeController::class, 'filter'])->name('transportType.filter')->middleware('permission:transport_type');
    Route::get('/contactAmos/filter', [ContactAmoController::class, 'filter'])->name('contactAmo.filter')->middleware('permission:amo_contact');
    Route::get('/contacts/filter', [ContactController::class, 'filter'])->name('contact.filter')->middleware('permission:contact');
    Route::get('/deliveries/filter', [DeliveryController::class, 'filter'])->name('delivery.filter')->middleware('permission:delivery');
    Route::get('/shiping_prices/filter', [ShipingPriceController::class, 'filter'])->name('shiping_price.filter')->middleware('permission:delivery_price');
    Route::get('/categories/filter', [CategoryController::class, 'filter'])->name('category.filter')->middleware('permission:category_product');
    Route::get('/orderpositions/filter', [OrderPositionController::class, 'filter'])->name('orderposition.filter')->middleware('permission:order_position');

    // Техкарты
    Route::resource('techcharts', TechChartController::class)->only([
        'index',
        'show'
    ])->middleware('permission:techchart');

    Route::get('/techchart/products', [TechChartController::class, 'products'])->name('techcharts.products')->middleware('permission:techchart');
    Route::get('/techchart/materials', [TechChartController::class, 'materials'])->name('techcharts.materials')->middleware('permission:techchart');

    // Техоперации
    Route::resource('processings', ProcessingController::class)->only([
        'index',
        'show'
    ])->middleware('permission:techprocess');
    Route::get('/processing/products', [ProcessingController::class, 'products'])->name('processings.products')->middleware('permission:techprocess');
    Route::get('/processing/materials', [ProcessingController::class, 'materials'])->name('processings.materials')->middleware('permission:techprocess');

    Route::get('/summary', [SummaryController::class, 'index'])->name('summary.index')->middleware('permission:report_summary');
    Route::get('/summary/remains', [SummaryController::class, 'remains'])->name('summary.remains')->middleware('permission:report_summary_remains');


    // Permission
    Route::get('/permission', [UsersController::class, 'permission'])->name('permission')->middleware('permission:user_permission');

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UsersController::class, 'users'])->name('all')->middleware('permission:user');
        Route::get('/{role}', [UsersController::class, 'users'])->where('role', 'operator|manager|dispatcher|carrier|audit')->name('roles')->middleware('permission:user');


        Route::resource('managment', UsersController::class)->only([
            'create',
            'store',
            'edit',
            'update',
            'destroy'
        ])->middleware('permission:user');
    });

    Route::get('/options/filter', [OptionController::class, 'filter'])->name('option.filter')->middleware('permission:option');
});


Route::get('/carrier/mypage', [CarrierController::class, 'index'])->name('carrier.index');


require __DIR__ . '/auth.php';
