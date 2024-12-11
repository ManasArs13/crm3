<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    public function getByName(Request $request)
    {
        $deliveries = Delivery::query()
        ->where('name', 'LIKE', '%'.$request->query('q').'%')
        ->orderBy("name")->paginate(10,  ['*'], 'page', request()->query('page'));

        return response()->json($deliveries);
    }

    public function getBetonPrice($id)
    {
        return  Delivery::where("id", $id)->first()?->betonprice;
    }
}
