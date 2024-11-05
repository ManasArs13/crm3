<?php

namespace App\Http\Controllers;

use App\Filters\ErrorFilter;
use App\Http\Requests\ErrorRequest;
use App\Models\Errors;
use App\Models\ErrorTypes;
use App\Models\User;
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
        $builder = Errors::query()->with(['type', 'responsible']);

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
        $statusChecked = 'index';
        $allowedChecked = 'index';
        $responsibleChecked = 'index';

        $status = [['value' => 'index', 'name' => 'Все'], ['value' => 1, 'name' => '1'], ['value' => 0, 'name' => '0']];
        $allowed = [['value' => 'index', 'name' => 'Все'], ['value' => 1, 'name' => 'Допущен'], ['value' => 0, 'name' => 'Не допущен']];
        $responsible[] = ['value' => 'index', 'name' => 'Все'];
        $types[] = ['value' => 'index', 'name' => 'Все типы'];
        $typesQuery = ErrorTypes::All();
        foreach ($typesQuery as $val){
            $types[] = [ 'value' => $val->id, 'name' => $val->name ];
        }
        $responsibleQuery = User::All();
        foreach ($responsibleQuery as $val){
            $responsible[] = [ 'value' => $val->id, 'name' => $val->name ];
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
                } else if ($key == 'status'){
                    $statusChecked = $value;
                } else if ($key == 'allowed'){
                    $allowedChecked = $value;
                } else if ($key == 'responsible'){
                    $responsibleChecked = $value;
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



            [
                'type' => 'select',
                'name' => 'status',
                'name_rus' => 'Статус',
                'values' => $status,
                'checked_value' => $statusChecked,
            ],
            [
                'type' => 'select',
                'name' => 'allowed',
                'name_rus' => 'Допущен',
                'values' => $allowed,
                'checked_value' => $allowedChecked,
            ],
            [
                'type' => 'select',
                'name' => 'responsible',
                'name_rus' => '	Ответственный',
                'values' => $responsible,
                'checked_value' => $responsibleChecked,
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

    public function edit($id)
    {
        $error = Errors::findOrFail($id);
        $errorTypes = ErrorTypes::All();
        $users = User::All();
        return view('errors.edit', compact('error', 'errorTypes', 'users'));
    }

    public function update(Request $request, string $id){
        $error = Errors::find($id);
        $error->fill($request->post())->save();
        return redirect()->back()->with('success', 'Данные успешно сохранены');
    }
}
