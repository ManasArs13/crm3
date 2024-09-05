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
        ->where('ms_id', 'c3308ff8-b57a-11ec-0a80-03c60005472c')
        ->orWhere('ms_id', '90054bd0-7c10-11e7-7a6c-d2a9003ab465')
        ->orderByDesc('id')->paginate(10);

        return response()->json($states);
    }
}
