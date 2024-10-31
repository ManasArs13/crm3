<?php

namespace App\Http\Controllers;

use App\Filters\ErrorFilter;
use App\Http\Requests\ErrorRequest;
use App\Models\Errors;
use App\Models\ErrorTypes;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:error')->only(['index']);
    }

    public function index(ErrorRequest $request){
        $entityName = 'Реестр ошибок';

        // Amo orders
        $builder = Errors::query()->with(['type']);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ErrorFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ErrorFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ErrorFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        $all_columns = [
            "id",
            "status",
            "allowed",
            "type",
            "link",
            "description",
            "responsible_user",
            "user_description",
            "created_at",
            "updated_at"
        ];

        $select = [
            "id",
            "status",
            "allowed",
            "type",
            "link",
            "description",
            "responsible_user",
            "user_description"
        ];

        $selected = $request->columns ?? $select;


        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Errors::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Errors::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Errors::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Errors::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $typeChecked = 'index';
        $types[] = ['value' => 'index', 'name' => 'Все типы'];
        $typesQuery = ErrorTypes::All();
        foreach ($typesQuery as $val){
            $types[] = [ 'value' => $val->id, 'name' => $val->name ];
        }

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
                } else if ($key == 'type'){
                    $typeChecked = $value;
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
                'name' => 'type',
                'name_rus' => 'Тип',
                'values' => $types,
                'checked_value' => $typeChecked,
            ],
        ];

        return view('errors.index', compact(
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
