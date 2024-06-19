<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function index(){
        $sumMaterials=Product::where("category_id", 8)->sum(DB::raw('residual * price'));
        $sumProducts=Product::whereIn("category_id", [5,6,7,11,12,15,16,21])->sum(DB::raw('residual * price'));
        $sumMutualSettlement=Contact::whereNot("balance",NULL)->sum("balance");
        $sumMutualSettlementMain=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', '8');
        })->whereNot("balance",NULL)->sum("balance");

        $sumBuyer=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id', [4,5]);
        })->whereNot("balance",NULL)->sum("balance");



        $sumAnother=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[4,5, 8,9]);
        })->whereNot("balance",NULL)->sum("balance");

        $sumCarriers=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', '9');
        })->whereNot("balance",NULL)->sum("balance");

        $contactsMutualSettlement=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 8);
        })->where("balance","<" ,0)->get();
        $contactsCarrier=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', 9);
        })->where("balance","<" ,0)->get();
        $contactsBuyer=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[4,5]);
        })->where("balance","<" ,0)->get();
        $contactsAnother=Contact::whereHas('contact_categories', function($q) {
            $q->whereIn('contact_category_id',[4,5, 8,9]);
        })->where("balance","<" ,0)->get();





        return view("summary.index", compact("sumMaterials", "sumProducts", "sumMutualSettlement","sumMutualSettlementMain", "sumCarriers", "contactsMutualSettlement", "contactsCarrier", "contactsBuyer", "contactsAnother","sumBuyer","sumAnother"));
    }
}
