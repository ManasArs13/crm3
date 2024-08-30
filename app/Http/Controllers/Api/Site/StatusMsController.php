<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Status;

class StatusMsController extends Controller
{
    public function getByName(Request $request)
    {
        $states = Status::query()
        ->where('name', 'LIKE', '%'.$request->query('q').'%')
        ->where('id', 1)
        ->orWhere('id', 4)
        ->orderByDesc('id')->paginate(10);

        return response()->json($states);
    }
}
