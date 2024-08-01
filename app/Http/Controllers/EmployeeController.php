<?php

namespace App\Http\Controllers;

use App\Filters\EmployeeFilter;
use App\Models\Employee;
use App\Models\Product;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $urlEdit = "employee.edit";
        $urlShow = "employee.show";
        $urlDelete = "employee.destroy";
        $urlCreate = "employee.create";
        $urlFilter = 'employee.index';
        $entityName = 'Сотрудники';

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
        $builder = Employee::query()
            ->where('name', 'like', "%Менеджер%")
            ->with('orders')
            ->withCount(['orders', 'orders as new_orders' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                });
            }])
            ->withSum('orders', 'sum')
            ->withSum(['orders as new_orders_sum' => function (Builder $query) use ($date) {
                $query->whereHas('contact', function ($queries) use ($date) {
                    $queries
                        ->whereMonth('created_at', $date)
                        ->whereYear('created_at', date('Y'));
                });
            }], 'sum');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderByDesc('id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "name",
            "firstName",
            'middleName',
            'lastName',
            'fullName',
            'shortFio',
            'position',
            'email',
            'phone',
            'salary',
            'uid',
            'archived',
            "count_orders",
            'sum_orders',
            "percent",
            "new_orders",
            "sum_new_orders",
            "percent_new_orders",
            "created_at",
            "updated_at",
            "ms_id"
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                "name",
                "count_orders",
                'sum_orders',
                "percent",
                "new_orders",
                "sum_new_orders",
                "percent_new_orders",
            ];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Employee::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Employee::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Employee::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Employee::query()->max('updated_at');
        $maxUpdatedCheck = '';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at') {
                    if ($value['max']) {
                        $maxCreatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minCreatedCheck = $value['min'];
                    }
                } else if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                }
            }
        }

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'minChecked' => $minCreatedCheck,
                'max' => substr($maxCreated, 0, 10),
                'maxChecked' => $maxCreatedCheck
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'minChecked' => $minUpdatedCheck,
                'max' => substr($maxUpdated, 0, 10),
                'maxChecked' => $maxUpdatedCheck
            ]
        ];

        return view("employee.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }

    public function index_block(Request $request)
    {
        $urlEdit = "employee.edit";
        $urlShow = "employee.show";
        $urlDelete = "employee.destroy";
        $urlCreate = "employee.create";
        $urlFilter = 'employee.index';
        $entityName = 'Сотрудники';

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
        $builder = Employee::query()
            ->where('name', 'like', "%Менеджер%")
            ->with('orders')
            ->withCount(['orders', 'orders as new_orders' => function (Builder $query) use ($date) {
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
            ->withSum('orders', 'sum')
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

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderByDesc('id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "name",
            "firstName",
            'middleName',
            'lastName',
            'fullName',
            'shortFio',
            'position',
            'email',
            'phone',
            'salary',
            'uid',
            'archived',
            "count_orders",
            'sum_orders',
            "percent",
            "new_orders",
            "sum_new_orders",
            "percent_new_orders",
            "created_at",
            "updated_at",
            "ms_id"
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                "name",
                "count_orders",
                'sum_orders',
                "percent",
                "new_orders",
                "sum_new_orders",
                "percent_new_orders",
            ];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Employee::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Employee::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Employee::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Employee::query()->max('updated_at');
        $maxUpdatedCheck = '';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at') {
                    if ($value['max']) {
                        $maxCreatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minCreatedCheck = $value['min'];
                    }
                } else if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                }
            }
        }

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'minChecked' => $minCreatedCheck,
                'max' => substr($maxCreated, 0, 10),
                'maxChecked' => $maxCreatedCheck
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'minChecked' => $minUpdatedCheck,
                'max' => substr($maxUpdated, 0, 10),
                'maxChecked' => $maxUpdatedCheck
            ],
        ];

        return view("employee.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }

    public function index_concrete(Request $request)
    {
        $urlEdit = "employee.edit";
        $urlShow = "employee.show";
        $urlDelete = "employee.destroy";
        $urlCreate = "employee.create";
        $urlFilter = 'employee.index';
        $entityName = 'Сотрудники';

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
        $builder = Employee::query()
            ->where('name', 'like', "%Менеджер%")
            ->with('orders')
            ->withCount(['orders', 'orders as new_orders' => function (Builder $query) use ($date) {
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
            ->withSum('orders', 'sum')
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

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new EmployeeFilter($builder, $request))->apply()->orderByDesc('id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "name",
            "firstName",
            'middleName',
            'lastName',
            'fullName',
            'shortFio',
            'position',
            'email',
            'phone',
            'salary',
            'uid',
            'archived',
            "count_orders",
            'sum_orders',
            "percent",
            "new_orders",
            "sum_new_orders",
            "percent_new_orders",
            "created_at",
            "updated_at",
            "ms_id"
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                "name",
                "count_orders",
                'sum_orders',
                "percent",
                "new_orders",
                "sum_new_orders",
                "percent_new_orders",
            ];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Employee::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Employee::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Employee::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Employee::query()->max('updated_at');
        $maxUpdatedCheck = '';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at') {
                    if ($value['max']) {
                        $maxCreatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minCreatedCheck = $value['min'];
                    }
                } else if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                }
            }
        }

        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'minChecked' => $minCreatedCheck,
                'max' => substr($maxCreated, 0, 10),
                'maxChecked' => $maxCreatedCheck
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'minChecked' => $minUpdatedCheck,
                'max' => substr($maxUpdated, 0, 10),
                'maxChecked' => $maxUpdatedCheck
            ]
        ];

        return view("employee.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
            'dateNext',
            'datePrev',
            'date',
            'dateRus'
        ));
    }
}
