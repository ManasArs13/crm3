<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Organization;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\Order;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class SummaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report_summary')->only(['index']);
        $this->middleware('permission:report_summary_remains')->only(['remains']);
    }

    public function index(Request $request){
        $sumMaterials=Product::where("category_id", 8)->sum(DB::raw('residual * price'));
        $sumProducts=Product::whereIn("category_id", [5,6,7,11,12,15,16,21])->sum(DB::raw('residual * price'));
        $sumMutualSettlement=Contact::whereNot("balance",NULL)->sum("balance"); // Взаиморасчеты наши
        $sumMutualSettlementMain=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 8);
        })->whereNot("balance",NULL)->sum("balance"); //Основные поставщики

        $mainSuppliers = Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 10);
        })->whereNot("balance",NULL)->sum("balance"); // Основные поставщики

        $msBalance = Organization::Sum('balance');
        $ourBalance = (Payment::selectRaw("
            SUM(CASE WHEN type IN ('cashin', 'paymentin') THEN sum ELSE 0 END) -
            SUM(CASE WHEN type IN ('cashout', 'paymentout') THEN sum ELSE 0 END) as balance
        ")->value('balance') ?? 0) / 100;

        $materialNorm = Option::where('code', 'norm_material')->first();

        $sumBuyer=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id', [4,5])->groupBy("contact_id");
        })->whereNot("balance",NULL)->sum("balance"); // Покупатели

        $sumAnother=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[24])->groupBy("contact_id");
        })->whereNot("balance",NULL)->sum("balance"); // Прочие

        $sumUnfilled=Contact::whereNotIn('contacts.id', function($subquery) {
            $subquery->select('contact_id')
                ->from('contact_contact_category')
                ->whereIn('contact_category_id', [10, 8, 9, 24, 4]);
        })->whereNotNull("balance")->where('balance', '!=', '0')->sum("balance"); // Незаполненные

        $sumCarriers=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 9);
        })->whereNot("balance",NULL)->sum("balance");

        $kassaAndTinkoff = Contact::WhereIn('name', ['Касса 2', 'Работникофф'])->first();

        $carriers_two = Shipment::whereMonth('created_at', date('m'))->sum('delivery_fee');

        $totals_two =
            $kassaAndTinkoff->firstWhere('name', 'Работникофф')->balance +
            $kassaAndTinkoff->firstWhere('name', 'Касса 2')->balance +
            $sumMaterials +
            $sumProducts +
            $msBalance +
            $sumMutualSettlementMain +
            $sumCarriers +
            $carriers_two;


        $total = $sumMutualSettlementMain + $mainSuppliers + $sumCarriers + $sumBuyer + $sumAnother + $sumUnfilled;


        return view("summary.index", compact(
            "sumMaterials","sumProducts", "sumMutualSettlement",
        "sumMutualSettlementMain", "mainSuppliers", "sumCarriers", "materialNorm",
        "sumBuyer","sumUnfilled", "sumAnother", "msBalance", "ourBalance", "total",
        "kassaAndTinkoff", "carriers_two", "totals_two"
        ));
    }

    public function remains( MoySkladService $service ){

        $cntShipmentsSite=Shipment::whereNull('deleted_at')->count();
        $cntOrdersSite=Order::whereNull('deleted_at')->count();
        $cntContactsSite=Contact::whereNull('deleted_at')->whereNot('is_archived', 1)->count();


        $urlContact = Option::where('code', '=', 'ms_counterparty_url')->first()?->value;
        $urlOrder = Option::where('code', '=', 'ms_orders_url')->first()?->value;
        $urlDemand = Option::where('code', '=', 'ms_url_demand')->first()?->value;


        $cntShipmentsMS=$service->createUrl($urlDemand,null, [], "", 2, false);
        $cntOrdersMS=$service->createUrl($urlOrder,null, ["isDeleted" => ["false"]], "", 2, false);
        $cntContactsMS=$service->createUrl($urlContact,null, [], "", 2, false);


        return view("summary.remains", compact("cntShipmentsSite", "cntOrdersSite", "cntContactsSite",
                                                "cntShipmentsMS", "cntOrdersMS", "cntContactsMS"));
    }
}
