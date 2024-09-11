<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebtorController extends Controller
{
    public function index(Request $request)
    {
        $contacts = Contact::whereDoesntHave('contact_categories', function ($q) {
            $q->where('contact_category_id', '=', '9');
        })
            ->selectRaw('contacts.id,
                        contacts.name,
                        contacts.balance,
                        contacts.ms_id,
                        contacts.description,
                        max(shipments.created_at) as moment,
                        DATEDIFF(CURDATE(), max(shipments.created_at)) as days')
            ->join('shipments', 'shipments.contact_id', '=', 'contacts.id')
            ->where("balance", "<", 0)
            ->groupBy('contact_id');

        $shipments0 = DB::table("shipments as sh")
            ->selectRaw('tab.moment, tab.id, tab.name, tab.balance, tab.ms_id, tab.description, sh.carrier_id, carriers.name as carrier')
            ->join(DB::raw('(' . $contacts->toSql() . ') as tab'), 'tab.id', 'sh.contact_id')
            ->join("carriers", "carriers.id", "sh.carrier_id")
            ->mergeBindings($contacts->getQuery())
            ->whereRaw('tab.moment=sh.created_at');

        $shipments = Shipment::selectRaw('shipments.id as ship,
                                        DATE_FORMAT(max(tab1.moment),"%d.%m.%Y") as moment,
                                        DATEDIFF(CURDATE(), max(tab1.moment)) as days,
                                        shipments.carrier_id,
                                        tab1.id,
                                        tab1.name,
                                        tab1.balance,
                                        tab1.ms_id,
                                        tab1.description,
                                        tab1.carrier_id,
                                        tab1.carrier,
                                        count(*) as cnt')
            ->rightJoin(DB::raw('(' . $shipments0->toSql() . ') as tab1'), function ($join) {
                $join->on('tab1.carrier_id', '=', 'shipments.carrier_id');
                $join->on("tab1.moment", "<", "shipments.created_at");
            })
            ->mergeBindings($shipments0);
        // ->orderBy('days', 'asc')
        // ->orderBy('moment', 'asc')
        //->groupBy("shipments.carrier_id", "tab1.id");

        // Columns
        $all_columns = ['name', 'moment', 'days', 'balance', 'description', 'ship'];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = ['name', 'moment', 'days', 'balance', 'description', 'ship'];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $shipments->orderBy($request->column)->groupBy("shipments.carrier_id", "tab1.id")->get();
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = $shipments->orderByDesc($request->column)->groupBy("shipments.carrier_id", "tab1.id")->get();
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $entityItems = $shipments->orderBy('days', 'asc')
                ->orderBy('moment', 'asc')->groupBy("shipments.carrier_id", "tab1.id")->get();
            $orderBy = 'desc';
            $selectColumn = null;
        }

        return view('debtor.index', compact('shipments', 'entityItems', 'resColumns', 'orderBy', 'selectColumn'));
    }
}