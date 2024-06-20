<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Product;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $query->select(DB::raw('distinct(t1.`transport_id`)'))
            ->from("shipments as t1")
            ->join(DB::raw('(select min(id) as id, transport_id from shipments where status <>"Оплачен" and transport_id is not null group by transport_id) as t0'),'t1.transport_id', '=', 't0.transport_id')
            ->where('t1.id','>','t0.id');
        })->where('status','<>','Оплачен')->get();

        return view("summary.index", compact("sumMaterials",
        "sumProducts", "sumMutualSettlement",
        "sumMutualSettlementMain", "sumCarriers",
        "contactsMutualSettlement", "contactsCarrier",
        "contactsBuyer", "contactsAnother",
        "sumBuyer","sumAnother",
        "sumMutualSettlementMainDebt", "sumBuyerDebt",
        "sumAnotherDebt","sumCarriersDebt","shipments"));
    }
}
