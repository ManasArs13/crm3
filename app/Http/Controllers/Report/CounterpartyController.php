<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Product;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;


class CounterpartyController extends Controller
{
    public function index(Request $request)
    {
        $entityName = 'Сводка "контрагенты"';

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

        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Managers
        $builder = Contact::query()->orderBy('name')
            ->whereHas('orders', function ($query) use ($date) {
                $query
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->orWhereHas('shipments', function ($query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->withCount(['orders as orders_count' => function (Builder $query) use ($date) {
                $query
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }])
            ->withSum(['orders as orders_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }], 'sum')
            ->withCount(['shipments as shipments_count' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }])
            ->withSum(['shipments as shipments_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }], 'suma');

        $entityItems = $builder->orderBy('id')->get();

        $selected = [
            "name",
            "count_orders",
            'sum_orders',
            "count_shipments",
            "sum_shipments",
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.counterparty", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }

    public function block(Request $request)
    {
        $entityName = 'Сводка "контрагенты"';

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

        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Managers
        $builder = Contact::query()->orderBy('name')
            ->whereHas('orders', function ($query) use ($date) {
                $query
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    })
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->orWhereHas('shipments', function ($query) use ($date) {
                $query
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->withCount(['orders as orders_count' => function (Builder $query) use ($date) {
                $query
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }])
            ->withSum(['orders as orders_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }], 'sum')
            ->withCount(['shipments as shipments_count' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }])
            ->withSum(['shipments as shipments_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }], 'suma');

        $entityItems = $builder->orderBy('id')->get();

        $selected = [
            "name",
            "count_orders",
            'sum_orders',
            "count_shipments",
            "sum_shipments",
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.counterparty", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }

    public function concrete(Request $request)
    {
        $entityName = 'Сводка "контрагенты"';

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

        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        // Managers
        $builder = Contact::query()->orderBy('name')
            ->whereHas('orders', function ($query) use ($date) {
                $query
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    })
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->orWhereHas('shipments', function ($query) use ($date) {
                $query
                    ->whereHas('products', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    })
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->withCount(['orders as orders_count' => function (Builder $query) use ($date) {
                $query
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }])
            ->withSum(['orders as orders_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereIn('status_id', [5, 6])
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }], 'sum')
            ->withCount(['shipments as shipments_count' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }])
            ->withSum(['shipments as shipments_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }], 'suma');

        $entityItems = $builder->orderBy('id')->get();

        $selected = [
            "name",
            "count_orders",
            'sum_orders',
            "count_shipments",
            "sum_shipments",
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.counterparty", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }
}
