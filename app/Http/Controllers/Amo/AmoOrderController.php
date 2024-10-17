<?php

namespace App\Http\Controllers\Amo;

use App\Filters\Amo\AmoOrderFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Amo\AmoOrderRequest;
use App\Models\ContactAmo;
use App\Models\Order;
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

        // Filters
        $minCreated = Order::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Order::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Order::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Order::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $contacts = [];



        $queryMaterial = 'index';
        $queryPlan = 'today';

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
                } else if ($key == 'contacts') {
                    $contact_names_get = ContactAmo::WhereIn('id', $value)->get(['id', 'phone']);

                    if (isset($value)) {
                        $contacts = [];
                        foreach ($contact_names_get as $val){
                            $contacts[] = [
                                'value' => $val->id,
                                'name' => $val->phone
                            ];
                        }
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
            [
                'type' => 'select2',
                'name' => 'contacts',
                'name_rus' => 'Контакты',
                'values' => $contacts,
            ],
        ];

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
