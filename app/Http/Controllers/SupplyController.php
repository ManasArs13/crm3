<?php

namespace App\Http\Controllers;

use App\Filters\SupplyFilter;
use App\Models\Supply;
use App\Models\SupplyPosition;
use Illuminate\Http\Request;

class SupplyController extends Controller
{
    public function index(Request $request)
    {
        $entityName = 'Приёмки';
        $urlEdit = "supply.edit";
        $urlShow = "supply.show";
        $urlDelete = "supply.destroy";
        $urlCreate = "supply.create";
        $urlFilter = 'supply.index';

        //Supply
        $builder = Supply::query()->with('contact', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new SupplyFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new SupplyFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new SupplyFilter($builder, $request))->apply()->orderByDesc('id')->paginate(50);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "created_at",
            "updated_at",
            "name",
            "contact_id",
            "moment",
            'description',
            'sum',
            'incoming_number',
            'incoming_date',
            'ms_id'
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                "id",
                "created_at",
                "updated_at",
                "name",
                "contact_id",
                "moment",
                'description',
                'sum',
                'description',
            ];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Supply::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Supply::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Supply::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Supply::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $minMoment = Supply::query()->min('moment');
        $minMomentCkeck = '';
        $maxMoment = Supply::query()->max('moment');
        $maxMomentCheck = '';

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
                        $minMomentCheck = $value['min'];
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
                'name' =>  'date_plan',
                'name_rus' => 'Дата приёмки',
                'min' => substr($minMoment, 0, 10),
                'minChecked' => $minMomentCkeck,
                'max' => substr($maxMoment, 0, 10),
                'maxChecked' => $maxMomentCheck
            ],
        ];

        return view("supply.index", compact(
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

    public function show(Request $request, $processing)
    {
        $needMenuForItem = true;
        $entity = 'supply';

        $supply = Supply::with('contact', 'products')->find($processing);

        return view('supply.show', compact("entity", 'supply'));
    }

    public function products(Request $request)
    {
        $needMenuForItem = true;
        $entity = 'supplies';

        $supply_products = SupplyPosition::with('supply', 'products')->orderBy('created_at', 'desc')->paginate(100);

        return view('supply.products', compact("entity", 'supply_products'));
    }
}
