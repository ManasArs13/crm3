<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Product;
use App\Models\ShipingPrice;
use App\Models\Shipment;
use App\Models\TransportType;
use App\Models\Date;
use App\Models\Time;
use App\Models\Contact;
use App\Models\Order;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CalculatorController extends Controller
{
    public function block()
    {
        $entity = 'calculator';
        $needMenuForItem = true;
        $states = Status::all();

        $pallet = Product::where("ms_id", "fe06d62d-87db-11e7-7a6c-d2a900041517")->first();

        $vehicleTypes = TransportType::whereNot('ms_id', '5c2ad6bd-3dcf-11ee-0a80-105c001170bb')
            ->whereNot('ms_id', '8caf01fa-34f2-11ee-0a80-139c002ba64a')
            ->whereNot('ms_id', 'c518da75-a146-11ec-0a80-0da500133bca')
            ->orderBy('name', 'asc')
            ->get();

        $vehicleTypesBeton = TransportType::where('ms_id', '8caf01fa-34f2-11ee-0a80-139c002ba64a')
            ->orderBy('name', 'asc')
            ->get();

        $dateNow = (new \DateTime())->format("Y-m-d");
        $dateFinish = (new \DateTime())->modify('+10 day')->format("Y-m-d");

        $dateNowQuery = $dateNow . ' 00:00.000';
        $dateFinishQuery = $dateFinish . ' 23:59.000';

        $datesCalc = DB::table('orders')
            ->join('order_positions', 'orders.id', '=', 'order_positions.order_id')
            ->join('products', 'products.id', '=', 'order_positions.product_id')
            ->select(
                DB::raw('orders.name as name, SUM(order_positions.weight_kg)/1000 as weight'),
                DB::raw('DATE_FORMAT(orders.date_plan, "%d.%m.%Y") as date'),
                DB::raw("CONCAT(DATE_FORMAT(orders.date_plan, '%H'),':00:00.000') as time")
            )
            ->whereIn("category_id", ['6', '12', '21', '15', '11'])
            ->whereBetween("orders.date_plan", [$dateNowQuery, $dateFinishQuery])
            ->whereNotIn("orders.status_id", [1, 2, 7])
            ->groupBy('orders.id')
            ->get();

        $datesBlock = DB::table('orders')
            ->join('order_positions', 'orders.id', '=', 'order_positions.order_id')
            ->join('products', 'products.id', '=', 'order_positions.product_id')
            ->select(
                DB::raw('orders.name as name, SUM(order_positions.weight_kg)/1000 as weight'),
                DB::raw('DATE_FORMAT(orders.date_plan, "%d.%m.%Y") as date'),
                DB::raw("CONCAT(DATE_FORMAT(orders.date_plan, '%H'),':00:00.000') as time")
            )
            ->whereNotNull("products.color_id")
            ->whereNotIn("orders.status_id", [1, 2, 7])
            ->whereBetween("orders.date_plan", [$dateNowQuery, $dateFinishQuery])
            ->groupBy('orders.id')
            ->get();

        $datesBeton = DB::table('orders')
            ->join('order_positions', 'orders.id', '=', 'order_positions.order_id')
            ->join('products', 'products.id', '=', 'order_positions.product_id')
            ->select(
                DB::raw('orders.name as name, SUM(order_positions.quantity) as quantity'),
                DB::raw('DATE_FORMAT(orders.date_plan, "%d.%m.%Y") as date'),
                DB::raw("CONCAT(DATE_FORMAT(orders.date_plan, '%H'),':00:00.000') as time")
            )
            ->where("category_id", '4')
            ->whereNotIn("orders.status_id", [1, 2, 7])
            ->whereBetween("orders.date_plan", [$dateNowQuery, $dateFinishQuery])
            ->groupBy('orders.id')
            ->get();

        $datesCalcFinish = [];
        foreach ($datesCalc as $date) {
            $datesCalcFinish[$date->date][$date->time]["items"][] = $date->name;
            if (!isset($datesCalcFinish[$date->date][$date->time]["weight"]))
                $datesCalcFinish[$date->date][$date->time]["weight"] = $date->weight;
            else
                $datesCalcFinish[$date->date][$date->time]["weight"] += $date->weight;
        }

        $datesBlockFinish = [];
        foreach ($datesBlock as $date) {
            $datesBlockFinish[$date->date][$date->time]["items"][] = $date->name;
            if (!isset($datesBlockFinish[$date->date][$date->time]["weight"]))
                $datesBlockFinish[$date->date][$date->time]["weight"] = $date->weight;
            else
                $datesBlockFinish[$date->date][$date->time]["weight"] += $date->weight;
        }

        $datesBetonFinish = [];
        foreach ($datesBeton as $date) {
            $datesBetonFinish[$date->date][$date->time]["items"][] = $date->name;
            if (!isset($datesBetonFinish[$date->date][$date->time]["quantity"]))
                $datesBetonFinish[$date->date][$date->time]["quantity"] = $date->quantity;
            else
                $datesBetonFinish[$date->date][$date->time]["quantity"] = $datesBetonFinish[$date->date][$date->time]["quantity"] + $date->quantity;
        }


        $dates = Date::where("is_active", 1)->where("date", ">=", $dateNow)->where("date", "<=", $dateFinish)->orderBy("date", "asc")->get();
        $times = Time::where("is_active", 1)->get();


        $products = Product::select("id", "ms_id", "name", "price", "category_id", 'color_id', "weight_kg", "residual as balance", "count_pallets")->with("category")->with("color")->whereNotNull("color_id")->whereNot("category_id", "7")->orderBy("name", "asc")->get();
        $betonProducts =  Product::select("id", "ms_id", "name", "price", "category_id", 'color_id', "weight_kg")->Where("category_id", "4")->orderBy("name", "asc")->get();

        $productsByGroup = [];
        $productsByBeton = [];
        $idCategory = 0;

        foreach ($products as $product) {
            $idCategory = $product->category_id;
            $nameCategory = $product->category->name;

            if ($product->ms_id == "a656eb95-be75-11ee-0a80-15e100320243") {
                $idCategory = $product->category_id . "_1";
                $nameCategory = $product->name;
            }

            $productsByGroup[$idCategory]["name"] = $nameCategory;
            $productsByGroup[$idCategory]["id"] = $idCategory;

            if ($product->color != null) {
                $productsByGroup[$idCategory]["colors"][] = [
                    "id" => $product->color_id,
                    "hex" => $product->color->hex,
                    "name" => $product->color->name,
                    "font_color" => $product->color->font_color,
                    "price" => $product->price,
                    "product" => $product->ms_id,
                    "weight" => $product->weight_kg,
                    "selected" => ($product->color_id == 5) ? "selected" : '',
                    "count_pallets" => $product->count_pallets,
                    "balance" => $product->balance
                ];
            }
        }

        $productsByFence = $productsByGroup;
        unset($productsByFence[$idCategory]);
        unset($productsByFence[7]);
        unset($productsByFence[18]);
        unset($productsByFence[5]);

        $idBeton = 0;
        foreach ($betonProducts as $product) {
            if ($idBeton == 0)
                $idBeton = $product->id;

            $productsByBeton[$product->id]["name"] = $product->name;
            $productsByBeton[$product->id]["id"] = $product->id;
            $productsByBeton[$product->id]["price"] = $product->price;
            $productsByBeton[$product->id]["weight"] = ceil($product->weight_kg);
            $productsByBeton[$product->id]["product"] = $product->ms_id;
        }

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
            ->groupBy('contact_id')
            ->orderBy('days', 'asc')->orderBy('moment', 'asc');

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
            ->mergeBindings($shipments0)
            ->orderBy('days', 'asc')->orderBy('moment', 'asc')
            ->groupBy("shipments.carrier_id", "tab1.id")->get();

        $date = date('Y-m-d');

        $orders = Order::query()->with(
            'positions',
            'status',
            'delivery',
            'transport',
            'contact',
            'transport_type'
        )
            ->whereDate('date_plan', $date)
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->whereIn('status_id', [3, 4, 5, 6])
            ->orderBy('date_plan')
            ->get();

        $resColumns = [];

        $columns = [
            "name",
            "contact_id",
            "sum",
            "date_plan",
            "status_id",
            "comment",
            "positions_count",
            // 'is_demand',
            // "residual_count",
            "delivery_id",
        ];

        $urlShow = "order.show";

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        return view(
            "calculator.calculator",
            compact(
                "needMenuForItem",
                "entity",
                "productsByFence",
                'vehicleTypes',
                'productsByGroup',
                'productsByBeton',
                'vehicleTypesBeton',
                'shipments',
                'dates',
                'times',
                'idBeton',
                'datesBlockFinish',
                'datesBetonFinish',
                'pallet',
                'orders',
                'resColumns',
                'urlShow'
            )
        );
    }

    public function debtors(Request $request)
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

        return view('calculator.debtors', compact('shipments', 'entityItems', 'resColumns', 'orderBy', 'selectColumn'));
    }
}
