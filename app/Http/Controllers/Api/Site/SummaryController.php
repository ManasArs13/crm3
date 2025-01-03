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
        $excludedCategories = [10, 8, 9, 24, 4];

        $query = Contact::select(
                'contacts.*',
                DB::raw('GREATEST(IFNULL(max_shipments.created_at, "0000-00-00"), IFNULL(max_supplies.moment, "0000-00-00"), IFNULL(max_payments.moment, "0000-00-00")) as latest_created_at'),
                DB::raw('DATEDIFF(CURDATE(), GREATEST(IFNULL(max_shipments.created_at, "0000-00-00"), IFNULL(max_supplies.moment, "0000-00-00"), IFNULL(max_payments.moment, "0000-00-00"))) as days_since_latest')
            )->when($type !== 'all', function ($query) use ($type, $excludedCategories){
                if ($type === 'other') {
                    $query->whereNotIn('contacts.id', function($subquery) use ($excludedCategories) {
                        $subquery->select('contact_id')
                            ->from('contact_contact_category')
                            ->whereIn('contact_category_id', $excludedCategories);
                    });
                } else {
                    $query->whereHas('contact_categories', function ($q) use ($type) {
                        $q->where('contact_category_id', '=', $type ?? 10);
                    });
                }
            })
            ->whereNotNull("balance")
            ->where('balance', '!=', '0')
            ->leftJoin(DB::raw('(SELECT contact_id, MAX(created_at) as created_at FROM shipments GROUP BY contact_id) as max_shipments'), 'contacts.id', '=', 'max_shipments.contact_id')
            ->leftJoin(DB::raw('(SELECT contact_id, MAX(moment) as moment FROM supplies GROUP BY contact_id) as max_supplies'), 'contacts.id', '=', 'max_supplies.contact_id')
            ->leftJoin(DB::raw('(SELECT contact_id, MAX(moment) as moment FROM payments GROUP BY contact_id) as max_payments'), 'contacts.id', '=', 'max_payments.contact_id');


        $mutualSettlements = $query->paginate(300, ['*'], 'page', $page);

        $paginationHtml = $mutualSettlements->appends(request()->query())->links()->render();

        return response()->json([
            'data' => $mutualSettlements->items(),
            'pagination' => $paginationHtml
        ]);
    }
}
