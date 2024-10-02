<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Transport;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TransporterController extends Controller
{
    public function index(Request $request)
    {
        $entityName = 'Сводка - Перевозчик';

        $month_list = array(
            '01'  => 'январь',
            '02'  => 'февраль',
            '03'  => 'март',
            '04'  => 'апрель',
            '05'  => 'май',
            '06'  => 'июнь',
            '07'  => 'июль',
            '08'  => 'август',
            '09'  => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Transport
        $transport = Transport::query()
            ->with('contact')
            ->withCount(['shipments as shipments_count' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withSum(['shipments as price_norm' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_price_norm')
            ->withSum(['shipments as price' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_price')
            ->withSum(['shipments as delivery_fee' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_fee')
            ->whereNotNull('contact_id')
            ->get();

        $grouped = $transport->groupBy('contact_id');

        $entityItems = [];

        foreach ($grouped as $key => $group) {
            $entityItems[] = [
                'contact_name' => Contact::where('id', $key)->first()?->name,
                'shipments_count' => $group->sum('shipments_count'),
                'price_norm' => $group->sum('price_norm'),
                'price' => $group->sum('price'),
                'delivery_fee' => $group->sum('delivery_fee')
            ];
        }

        $selected = [
            //"name",
            "contact_name",
            "count_shipments",
            'price_norm',
            "price",
            "delivery_fee",
            // "difference_norm",
            // 'difference_norm_percent',
            "difference_price",
            'difference_price_percent',
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.transporter", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
        ));
    }

    public function block(Request $request)
    {
        $entityName = 'Сводка - Перевозчик';

        $month_list = array(
            '01'  => 'январь',
            '02'  => 'февраль',
            '03'  => 'март',
            '04'  => 'апрель',
            '05'  => 'май',
            '06'  => 'июнь',
            '07'  => 'июль',
            '08'  => 'август',
            '09'  => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Transport
        $transport = Transport::query()
            ->withCount(['shipments as shipments_count' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withSum(['shipments as price_norm' => function (Builder $query) use ($date, $dateY) {
                $query->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_price_norm')
            ->withSum(['shipments as price' => function (Builder $query) use ($date, $dateY) {
                $query->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_price')
            ->withSum(['shipments as delivery_fee' => function (Builder $query) use ($date, $dateY) {
                $query->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_fee')
            ->whereNotNull('contact_id')
            ->get();

        $grouped = $transport->groupBy('contact_id');

        $entityItems = [];

        foreach ($grouped as $key => $group) {
            $entityItems[] = [
                'contact_name' => Contact::where('id', $key)->first()?->name,
                'shipments_count' => $group->sum('shipments_count'),
                'price_norm' => $group->sum('price_norm'),
                'price' => $group->sum('price'),
                'delivery_fee' => $group->sum('delivery_fee')
            ];
        }

        $selected = [
            //"name",
            "contact_name",
            "count_shipments",
            'price_norm',
            "price",
            "delivery_fee",
            // "difference_norm",
            // 'difference_norm_percent',
            "difference_price",
            'difference_price_percent',
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.transporter", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
        ));
    }


    public function concrete(Request $request)
    {
        $entityName = 'Сводка - Перевозчик';

        $month_list = array(
            '01'  => 'январь',
            '02'  => 'февраль',
            '03'  => 'март',
            '04'  => 'апрель',
            '05'  => 'май',
            '06'  => 'июнь',
            '07'  => 'июль',
            '08'  => 'август',
            '09'  => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Transport
        $transport = Transport::query()
            ->withCount(['shipments as shipments_count' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }])
            ->withSum(['shipments as price_norm' => function (Builder $query) use ($date, $dateY) {
                $query->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_price_norm')
            ->withSum(['shipments as price' => function (Builder $query) use ($date, $dateY) {
                $query->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_price')
            ->withSum(['shipments as delivery_fee' => function (Builder $query) use ($date, $dateY) {
                $query->whereHas('products', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_fee')
            ->whereNotNull('contact_id')
            ->get();

        $grouped = $transport->groupBy('contact_id');

        $entityItems = [];

        foreach ($grouped as $key => $group) {
            $entityItems[] = [
                'contact_name' => Contact::where('id', $key)->first()?->name,
                'shipments_count' => $group->sum('shipments_count'),
                'price_norm' => $group->sum('price_norm'),
                'price' => $group->sum('price'),
                'delivery_fee' => $group->sum('delivery_fee')
            ];
        }

        $selected = [
            //"name",
            "contact_name",
            "count_shipments",
            'price_norm',
            "price",
            "delivery_fee",
            // "difference_norm",
            // 'difference_norm_percent',
            "difference_price",
            'difference_price_percent',
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.transporter", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
        ));
    }
}
