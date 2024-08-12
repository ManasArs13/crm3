<?php

namespace App\Http\Controllers;

use App\Filters\ManagerFilter;
use App\Models\Manager;
use App\Models\Order;
use App\Models\Product;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index(Request $request)
    {
        $urlEdit = "manager.edit";
        $urlShow = "manager.show";
        $urlDelete = "manager.destroy";
        $urlCreate = "manager.create";
        $urlFilter = 'manager.index';
        $entityName = 'Менеджеры';

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
        $builder = Manager::query()
            ->withCount(['orders as all_orders' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }])
            ->withSum(['orders as all_orders_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            }], 'sum')
            ->withCount(['orders as new_orders' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                });
            }])
            ->withSum(['orders as new_orders_sum' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                });
            }], 'sum');

        $entityItems = $builder->orderBy('id')->get();

        // Orders without manager
        $orders = Order::select('id', 'created_at', 'sum', 'manager_id')
            ->whereNull('manager_id')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', date('Y'))
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK)
                        ->orWhere('building_material', Product::CONCRETE);
                });
            })
            ->get();

        $ordersNew = Order::select('id', 'created_at', 'sum', 'manager_id')
            ->whereNull('manager_id')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', date('Y'))
            ->whereHas('contact', function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK)
                        ->orWhere('building_material', Product::CONCRETE);
                });
            })
            ->get();

        $selected = [
            "name",
            "count_orders",
            'sum_orders',
            "percent",
            "new_orders",
            "sum_new_orders",
            "percent_new_orders",
        ];


        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("manager.index", compact(
            'entityItems',
            'orders',
            'ordersNew',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }

    public function index_block(Request $request)
    {
        $urlEdit = "manager.edit";
        $urlShow = "manager.show";
        $urlDelete = "manager.destroy";
        $urlCreate = "manager.create";
        $urlFilter = 'manager.index';
        $entityName = 'Менеджеры';

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

        // Orders
        $builder = Manager::query()
            ->withCount(['orders as all_orders' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'))
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    });
            }])
            ->withSum(['orders as all_orders_sum' => function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'))
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    });
            }], 'sum')
            ->withCount(['orders as new_orders' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                })
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    });
            }])
            ->withSum(['orders as new_orders_sum' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                })
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::BLOCK);
                        });
                    });
            }], 'sum');

        $entityItems = $builder->orderBy('id')->get();

        // Orders without manager
        $orders = Order::select('id', 'created_at', 'sum', 'manager_id')
            ->whereNull('manager_id')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', date('Y'))
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK);
                });
            })
            ->get();

        $ordersNew = Order::select('id', 'created_at', 'sum', 'manager_id')
            ->whereNull('manager_id')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', date('Y'))
            ->whereHas('contact', function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK);
                });
            })
            ->get();

        $selected = [
            "name",
            "count_orders",
            'sum_orders',
            "percent",
            "new_orders",
            "sum_new_orders",
            "percent_new_orders",
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("manager.index", compact(
            'entityItems',
            'orders',
            'ordersNew',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }

    public function index_concrete(Request $request)
    {
        $urlEdit = "manager.edit";
        $urlShow = "manager.show";
        $urlDelete = "manager.destroy";
        $urlCreate = "manager.create";
        $urlFilter = 'manager.index';
        $entityName = 'Менеджеры';

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

        // Orders
        $builder = Manager::query()
            ->withCount(['orders as all_orders' => function (Builder $que) use ($date) {
                $que
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'))
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    });
            }])
            ->withSum(['orders as all_orders_sum' => function (Builder $que) use ($date) {
                $que
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'))
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    });
            }], 'sum')
            ->withCount(['orders as new_orders' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                })
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    });
            }])
            ->withSum(['orders as new_orders_sum' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                })
                    ->whereHas('positions', function ($query) {
                        $query->whereHas('product', function ($queries) {
                            $queries->where('building_material', Product::CONCRETE);
                        });
                    });
            }], 'sum');


        $entityItems = $builder->orderBy('id')->get();

        // Orders without manager
        $orders = Order::select('id', 'created_at', 'sum', 'manager_id')
            ->whereNull('manager_id')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', date('Y'))
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->get();

        $ordersNew = Order::select('id', 'created_at', 'sum', 'manager_id')
            ->whereNull('manager_id')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', date('Y'))
            ->whereHas('contact', function (Builder $query) use ($date) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', date('Y'));
            })
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->get();

        $selected = [
            "name",
            "count_orders",
            'sum_orders',
            "percent",
            "new_orders",
            "sum_new_orders",
            "percent_new_orders",
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("manager.index", compact(
            'entityItems',
            'orders',
            'ordersNew',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }
}
