<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use App\Models\Carrier;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    public function getCarriers(Request $request)
    {
        $phones = Carrier::query()
            ->Where('name', 'LIKE', '%'.$request->query('term') . '%')
            ->orderBy('name')->take(10)->get();

        return response()->json($phones);
    }
}
