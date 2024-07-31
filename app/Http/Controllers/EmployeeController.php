<?php

namespace App\Http\Controllers;

use App\Filters\EmployeeFilter;
use App\Models\Employee;
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

        // Orders
        $builder = Employee::query()
            ->with('orders')
            ->withCount(['orders', 'orders as new_orders' => function (Builder $query) {
                $query->whereHas('contact', function ($queries) {
                    $queries->whereMonth('created_at', now()->month);
                });
            }])
            ->withSum('orders', 'sum')
            ->withSum(['orders as new_orders_sum' => function (Builder $query) {
                $query->whereHas('contact', function ($queries) {
                    $queries->whereMonth('created_at', now()->month);
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
                "id",
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
        ));
    }
}
