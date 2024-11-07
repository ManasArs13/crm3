<?php

namespace App\Http\Controllers\Inventory;

use App\Filters\Inventory\EnterFilter;
use App\Http\Controllers\Controller;
use App\Models\Enter;
use Illuminate\Http\Request;

class EnterController extends Controller
{
    public function __construct()
    {
        // $this->middleware('permission:enter')->only(['index', 'show']);
        // $this->middleware('permission:enter_edit')->only(['index','show']);
    }

    public function index(Request $request)
    {
        $entity = 'enters';
        $urlShow = "enter.show";
        $urlFilter = 'enter.index';

        //Enter
        $builder = Enter::query()->with('positions');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new EnterFilter($builder, $request))->apply()->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new EnterFilter($builder, $request))->apply()->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new EnterFilter($builder, $request))->apply()->orderByDesc('id');
            $selectColumn = null;
        }

        // Итоги в таблице
        $totals = $this->total($entityItems);

        $entityItems = $entityItems->paginate(100);

        // Columns
        $all_columns = [
            "id",
            "created_at",
            "updated_at",
            "name",
            "moment",
            'description',
            'sum',
            'ms_id'
        ];

        $select = [
            "id",
            "created_at",
            "updated_at",
            "name",
            "moment",
            'description',
            'sum',
        ];

        $selected = $request->columns ?? $select;


        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Enter::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Enter::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Enter::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Enter::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $minMoment = Enter::query()->min('moment');
        $minMomentCkeck = '';
        $maxMoment = Enter::query()->max('moment');
        $maxMomentCheck = '';

        $minSum = Enter::query()->min('sum');
        $minSumCheck = '';
        $maxSum = Enter::query()->max('sum');
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
                    if ($value['max']) {
                        $maxMomentCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minMomentCkeck = $value['min'];
                    }
                } else if ($key == 'sum') {
                    if ($value['max']) {
                        $maxSumCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minSumCheck = $value['min'];
                    }
                } else if ($key == 'contact') {
                    $queryContact = $value;
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
                'name_rus' => 'Дата приёмки',
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
                'minChecked' => $minSumCheck,
                'max' => substr($maxSum, 0, 10),
                'maxChecked' => $maxSumCheck
            ],
        ];

        return view("inventory.enter.index", compact(
            'select',
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
            'entity',
            'totals'
        ));
    }

    public function show(Request $request, $enter)
    {
        $needMenuForItem = true;
        $entity = 'enter';

        $enter = Enter::with('positions')->find($enter);

        return view('inventory.enter.show', compact("entity", 'enter'));
    }

    public function total($entityItems){
        return [
            'total_sum' => $entityItems->sum('sum'),
        ];
    }
}
