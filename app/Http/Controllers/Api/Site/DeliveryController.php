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
        ->where('ms_id','<>' ,'28803b00-5c8f-11ea-0a80-02ed000b1ce1')
        ->orderBy("name")->paginate(10,  ['*'], 'page', request()->query('page'));

        return response()->json($deliveries);
    }
}
