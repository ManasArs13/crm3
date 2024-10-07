<?php

namespace App\Http\Controllers\Finance;

use App\Filters\PaymentFilter;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $entityName = 'Платежи';
        $urlFilter = 'finance.index';

        $builder = Payment::query()->with('contact');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new PaymentFilter($builder, $request))->apply()->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new PaymentFilter($builder, $request))->apply()->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $entityItems = (new PaymentFilter($builder, $request))->apply()->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $entityItems->paginate(50);

        // Columns
        $all_columns = [
            "name",
            "type",
            "operation",
            "moment",
            'description',
            "contact_id",
            "sum",
            "created_at",
            'updated_at',
        ];

        $select = [
            "name",
            "type",
            "moment",
            'description',
            "contact_id",
            "sum",
            "created_at",
            'updated_at',
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Payment::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Payment::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Payment::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Payment::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $minMoment = Payment::query()->min('moment');
        $minMomentCkeck = '';
        $maxMoment = Payment::query()->max('moment');
        $maxMomentCheck = '';

        $minSum = (int) Payment::query()->min('sum');
        $minSumCkeck = '';
        $maxSum = (int) Payment::query()->max('sum');
        $maxSumCheck = '';

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
                } else if ($key == 'moment') {
                    if ($value['min']) {
                        $minMomentCkeck = $value['min'];
                    }
                    if ($value['max']) {
                        $maxMomentCheck = $value['max'];
                    }
                } else if ($key == 'sum') {
                    if ($value['min']) {
                        $minSumCkeck = $value['min'];
                    }
                    if ($value['max']) {
                        $maxSumCheck = $value['max'];
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
                'type' => 'date',
                'name' =>  'moment',
                'name_rus' => 'Фактическая дата',
                'min' => substr($minMoment, 0, 10),
                'minChecked' => $minMomentCkeck,
                'max' => substr($maxMoment, 0, 10),
                'maxChecked' => $maxMomentCheck
            ],
            [
                'type' => 'number',
                'name' =>  'sum',
                'name_rus' => 'Сумма',
                'min' => substr($minSum, 0, 10),
                'minChecked' => $minSumCkeck,
                'max' => substr($maxSum, 0, 10),
                'maxChecked' => $maxSumCheck
            ],
        ];
     //   dd($filters);
        return view("finance.payment", compact(
            'entityItems',
            'entityName',
            'urlFilter',
            "resColumns",
            "resColumnsAll",
            'orderBy',
            'selectColumn',
            'filters',
        ));
    }
}
