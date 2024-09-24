<?php

namespace App\Http\Controllers;

use App\Models\Contact;
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
    public function index(){
        $sumMaterials=Product::where("category_id", 8)->sum(DB::raw('residual * price'));
        $sumProducts=Product::whereIn("category_id", [5,6,7,11,12,15,16,21])->sum(DB::raw('residual * price'));
        $sumMutualSettlement=Contact::whereNot("balance",NULL)->sum("balance");
        $sumMutualSettlementMain=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 8);
        })->whereNot("balance",NULL)->sum("balance");

        $sumBuyer=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id', [4,5])->groupBy("contact_id");
        })->whereNot("balance",NULL)->sum("balance");

        $sumAnother=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[4,5, 8,9])->groupBy("contact_id");
        })->whereNot("balance",NULL)->sum("balance");

        $sumCarriers=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 9);
        })->whereNot("balance",NULL)->sum("balance");

        $contactsMutualSettlement=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 8);
        })->where("balance","<" ,0)->orderBy("balance", "asc")->get();
        $contactsCarrier=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 9);
        })->where("balance","<" ,0)->orderBy("balance", "asc")->get();
        $contactsBuyer=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[4,5])->groupBy("contact_id");
        })->where("balance","<" ,0)->orderBy("balance", "asc")->get();
        $contactsAnother=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[4,5, 8,9])->groupBy("contact_id");
        })->where("balance","<" ,0)->orderBy("balance", "asc")->get();


        $sumMutualSettlementMainDebt=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', '8');
        })->where("balance","<",0)->sum("balance");

        $sumBuyerDebt=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id', [4,5])->groupBy("contact_id");
        })->where("balance","<",0)->sum("balance");

        $sumAnotherDebt=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[4,5, 8,9])->groupBy("contact_id");
        })->where("balance","<",0)->sum("balance");

        $sumCarriersDebt=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', '9');
        })->where("balance","<",0)->sum("balance");

        $shipments=Shipment::whereIn('transport_id', function($query){
            $query->select(DB::raw('distinct(t1.transport_id)'))
            ->from("shipments as t1")
            ->join(DB::raw('(select min(id) as id, transport_id from shipments where status <>"Оплачен" and transport_id is not null group by transport_id) as t0'),'t1.transport_id', '=', 't0.transport_id')
            ->whereRaw('t1.id > t0.id');
        })
        ->select('shipments.name', 'transports.name as transportName')
        ->join("transports", "transports.id","=","transport_id")
        ->with("transport")
        ->where('shipments.status','<>','Оплачен')->orderBy("shipments.name", "asc")->get();

        return view("summary.index", compact("sumMaterials",
        "sumProducts", "sumMutualSettlement",
        "sumMutualSettlementMain", "sumCarriers",
        "contactsMutualSettlement", "contactsCarrier",
        "contactsBuyer", "contactsAnother",
        "sumBuyer","sumAnother",
        "sumMutualSettlementMainDebt", "sumBuyerDebt",
        "sumAnotherDebt","sumCarriersDebt","shipments"));
    }

    public function remains( MoySkladService $service ){

        $cntShipmentsSite=Shipment::count();
        $cntOrdersSite=Order::whereNull('deleted_at')->count();
        $cntContactsSite=Contact::count();


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
