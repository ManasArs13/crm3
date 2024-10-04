<?php

namespace App\Http\Controllers\Production;

use App\Filters\ProcessingFilter;
use App\Filters\ProcessingProductFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessingRequest;
use App\Models\TechProcess;
use App\Models\TechProcessMaterial;
use App\Models\TechProcessProduct;
use Illuminate\Http\Request;

class ProcessingController extends Controller
{
    public function index(ProcessingRequest $request)
    {
        $urlShow = "processings.show";
        $urlFilter = 'processings.index';
        $entityName = 'Тех-операции';

        $builder = TechProcess::query()->with('tech_chart:id,name', 'products:id,name');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ProcessingFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ProcessingFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ProcessingFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "name",
            "moment",
            "created_at",
            "updated_at",
            "description",
            "tech_chart_id",
            "quantity",
            "sum",
            "hours",
            "cycles",
            "defective",
            "ms_link"
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                "id",
                "name",
                "moment",
                "description",
                "tech_chart_id",
                "quantity",
                "sum",
                "hours",
                "cycles",
                "defective",
                "ms_link"
            ];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = TechProcess::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = TechProcess::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = TechProcess::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = TechProcess::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $minDatePlan = TechProcess::query()->min('moment');
        $minDatePlanCkeck = '';
        $maxDatePlan = TechProcess::query()->max('moment');
        $maxDatePlanCheck = '';

        $queryMaterial = 'index';

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
                        $minDatePlanCkeck = $value['min'];
                    }
                    if ($value['max']) {
                        $maxDatePlanCheck = $value['max'];
                    }
                } else if ($key == 'material') {
                    switch ($value) {
                        case 'concrete':
                            $queryMaterial = 'concrete';
                            break;
                        case 'block':
                            $queryMaterial = 'block';
                            break;
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
                'name_rus' => 'Плановая дата',
                'min' => substr($minDatePlan, 0, 10),
                'minChecked' => $minDatePlanCkeck,
                'max' => substr($maxDatePlan, 0, 10),
                'maxChecked' => $maxDatePlanCheck
            ],
            [
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' => $queryMaterial,
            ],
        ];


        return view('production.processing.index', compact(
            "entityName",
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
        ));
    }

    public function show(Request $request, $processing)
    {
        $needMenuForItem = true;
        $entity = 'processing';

        $processing = TechProcess::with('materials', 'products')->find($processing);

        return view('production.processing.show', compact("needMenuForItem", "entity", 'processing'));
    }

    public function products(Request $request)
    {
        $urlFilter = 'processings.products';
        $entityName = 'Тех-операции (Состав - продукты)';

        $builder = TechProcessProduct::query()->with('product:id,name');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ProcessingProductFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ProcessingProductFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ProcessingProductFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "processing_id",
            "product_id",
            "quantity",
            "sum",
            "created_at",
            "updated_at",
            "ms_id"
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                "id",
                "processing_id",
                "product_id",
                "quantity",
                "sum",
                "created_at",
                "updated_at",
                "ms_id"
            ];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = TechProcessProduct::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = TechProcessProduct::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = TechProcessProduct::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = TechProcessProduct::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $queryMaterial = 'index';

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
                        $minDatePlanCkeck = $value['min'];
                    }
                    if ($value['max']) {
                        $maxDatePlanCheck = $value['max'];
                    }
                } else if ($key == 'material') {
                    switch ($value) {
                        case 'concrete':
                            $queryMaterial = 'concrete';
                            break;
                        case 'block':
                            $queryMaterial = 'block';
                            break;
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
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' => $queryMaterial,
            ],
        ];

        return view('production.processing.products', compact(
            "entityName",
            'entityItems',
            "resColumns",
            "resColumnsAll",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
        ));
    }

    public function materials(Request $request)
    {
        $urlFilter = 'processings.materials';
        $entityName = 'Тех-операции (Состав - материалы)';

        $builder = TechProcessMaterial::query()->with('product:id,name');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ProcessingProductFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ProcessingProductFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ProcessingProductFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            "id",
            "processing_id",
            "product_id",
            "quantity",
            "created_at",
            "updated_at",
            "ms_id"
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = [
                "id",
                "processing_id",
                "product_id",
                "quantity",
                "created_at",
                "updated_at",
                "ms_id"
            ];
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = TechProcessMaterial::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = TechProcessMaterial::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = TechProcessMaterial::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = TechProcessMaterial::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $queryMaterial = 'index';

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
                        $minDatePlanCkeck = $value['min'];
                    }
                    if ($value['max']) {
                        $maxDatePlanCheck = $value['max'];
                    }
                } else if ($key == 'material') {
                    switch ($value) {
                        case 'concrete':
                            $queryMaterial = 'concrete';
                            break;
                        case 'block':
                            $queryMaterial = 'block';
                            break;
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
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => [['value' => 'index', 'name' => 'Все'], ['value' => 'block', 'name' => 'Блок'], ['value' => 'concrete', 'name' => 'Бетон']],
                'checked_value' => $queryMaterial,
            ],
        ];

        return view('production.processing.products', compact(
            "entityName",
            'entityItems',
            "resColumns",
            "resColumnsAll",
            'urlFilter',
            'filters',
            'orderBy',
            'selectColumn',
        ));
    }
}
