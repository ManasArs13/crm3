<?php

namespace App\Http\Controllers\Amo;

use App\Filters\Amo\AmoOrderFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Amo\AmoOrderRequest;
use App\Models\OrderAmo;

class AmoOrderController extends Controller
{
    public function index(AmoOrderRequest $request)
    {
        $entityName = 'Заказы АМО';

        // Amo orders
        $builder = OrderAmo::query()->with(['status_amo', 'contact_amo']);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new AmoOrderFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new AmoOrderFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new AmoOrderFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        $all_columns = [
            "id",
            "name",
            'status_amo_id',
            'contact_amo_id',
            'price',
            'comment',
            'is_exist',
            'order_link',
            'order_id',
            'created_at',
            'updated_at',
            'manager_id',
            'is_success'
        ];

        $select = [
            "id",
            "name",
            'status_amo_id',
            'contact_amo_id',
            'price',
            'comment',
            'is_exist',
            'order_link',
            'order_id',
            'created_at',
            'updated_at',
            'manager_id',
            'is_success'
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        $filters = [];

        return view('amo.order.index', compact(
            "resColumns",
            "resColumnsAll",
            'entityName',
            'entityItems',
            'orderBy',
            'selectColumn',
            'filters',
            'select'
        ));
    }
}
