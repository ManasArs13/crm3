<?php
namespace App\Http\Controllers\Api\Site;
use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SummaryController extends Controller
{
    public function getMutualSettlements(Request $request)
    {
        $type = $request->input('type');
        $page = $request->input('page', 1);

        $query = Contact::select(
                'contacts.*',
                DB::raw('GREATEST(IFNULL(max_shipments.created_at, "0000-00-00"), IFNULL(max_supplies.created_at, "0000-00-00")) as latest_created_at'),
                DB::raw('DATEDIFF(CURDATE(), GREATEST(IFNULL(max_shipments.created_at, "0000-00-00"), IFNULL(max_supplies.created_at, "0000-00-00"))) as days_since_latest')
            )
            ->whereHas('contact_categories', function ($q) use ($type) {
                if ($type === 'other') {
                    $q->whereNotIn('contact_category_id', [10, 8, 9, 21, 4]);
                } else {
                    $q->where('contact_category_id', '=', $type ?? 10);
                }
            })
            ->whereNotNull("balance")
            ->leftJoin(DB::raw('(SELECT contact_id, MAX(created_at) as created_at FROM shipments GROUP BY contact_id) as max_shipments'), 'contacts.id', '=', 'max_shipments.contact_id')
            ->leftJoin(DB::raw('(SELECT contact_id, MAX(created_at) as created_at FROM supplies GROUP BY contact_id) as max_supplies'), 'contacts.id', '=', 'max_supplies.contact_id');

        $mutualSettlements = $query->paginate(100, ['*'], 'page', $page);

        $paginationHtml = $mutualSettlements->appends(request()->query())->links()->render();

        return response()->json([
            'data' => $mutualSettlements->items(),
            'pagination' => $paginationHtml
        ]);
    }
}
