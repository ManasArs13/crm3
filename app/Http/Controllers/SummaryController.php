<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Product;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function index(){
        $sumMaterials=Product::where("category_id", 8)->sum("balance");
        $sumProducts=Product::whereIn("category_id", [4,5,6,7,11,12,15,16,21])->sum("balance");
        $sumMutualSettlement=Contact::whereNot("balance",NULL)->sum("balance");
        $sumMutualSettlementMain=Contact::whereHas('contact_categories', function($q) {
            $q->where('contact_category_id', '=', '11');
        })->whereNot("balance",NULL)->sum("balance");

        return view("summary.index", compact("sumMaterials", "sumProducts", "sumMutualSettlement","sumMutualSettlementMain"));
    }
}
