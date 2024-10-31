<?php

namespace App\Http\Controllers\Amo;

use App\Filters\Amo\CallFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Amo\AmoOrderRequest;
use App\Models\Call;
use App\Models\TalkAmo;

class CallController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:call')->only(['index']);
        $this->middleware('permission:conversation')->only(['conversations']);
    }

    public function index(AmoOrderRequest $request){
        $entityName = 'Звонки';

        // calls
        $builder = Call::query()->with(['employee']);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new CallFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new CallFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new CallFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }


        $all_columns = [
            'created_at',
            'updated_at',
            'amo_id',
            'duration',
            'employee_amo_id',
            'type',
        ];

        $select = [
            'created_at',
            'updated_at',
            'amo_id',
            'duration',
            'employee_amo_id',
            'type',
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Call::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Call::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Call::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Call::query()->max('updated_at');
        $maxUpdatedCheck = '';


        $type = [
            'outgoing_call' => 'исходящий вызов',
            'incoming_call' => 'входящий вызов',
        ];

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

        return view('amo.call', compact(
            "resColumns",
            "resColumnsAll",
            'entityName',
            'entityItems',
            'orderBy',
            'selectColumn',
            'filters',
            'select',
            'type'
        ));
    }
    public function conversations(AmoOrderRequest $request){
        $entityName = 'Беседы';

        // Amo orders
        $builder = TalkAmo::query()->with(['employee', 'contact_amo']);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new CallFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new CallFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new CallFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        $all_columns = [
            'created_at',
            'updated_at',
            'amo_id',
            'phone',
            'contact_amo_id',
            'employee_amo_id'
        ];

        $select = [
            'created_at',
            'updated_at',
            'amo_id',
            'phone',
            'contact_amo_id',
            'employee_amo_id'
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = TalkAmo::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = TalkAmo::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = TalkAmo::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = TalkAmo::query()->max('updated_at');
        $maxUpdatedCheck = '';


        $type = [
            'outgoing_call' => 'исходящий вызов',
            'incoming_call' => 'входящий вызов',
        ];

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

        return view('amo.call', compact(
            "resColumns",
            "resColumnsAll",
            'entityName',
            'entityItems',
            'orderBy',
            'selectColumn',
            'filters',
            'select',
            'type'
        ));
    }
}
