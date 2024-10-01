<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Shifts;
use App\Models\Transport;
use Illuminate\Support\Carbon;
use App\Http\Requests\Shift\ShiftChangeRequest;
use App\Http\Requests\Shift\ShiftCreateRequest;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index(FilterRequest $request, $shift = null){

        $entityItems = Shifts::query()->WhereDate('start_shift', Carbon::now())->with('transport');

        $entityItems->when($shift === 'onshift', function ($query) {
            return $query->whereNull('end_shift');
        })->when($shift === 'offshift', function ($query) {
            return $query->whereNotNull('end_shift');
        });

        $needMenuForItem = true;
        $urlEdit = "transport.edit";
        $urlDelete = "transport.destroy";
        $urlCreate = "transport.create";
        $urlFilter = 'transport.index';
        $urlShow = "transportType.show";
        $entityName = 'transports';
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

        // Columns
        $all_columns = [
            'id',
            'name',
            'driver',
            'description',
            'phone',
            'created_at',
            'updated_at',
            'tonnage',
            'contact_id',
            'ms_id',
            'type_id',
            'start_shift',
            'end_shift',
        ];

        $select = [
            "id",
            "name",
            'start_shift',
            'end_shift',
        ];

        $selected = $request->columns ?? $select;

        /* Сортировка */
        if (isset($request->orderBy)  && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
        } elseif (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =   $entityItems->paginate(50);
        }

        $resColumns = [];
        $resColumnsAll = [];

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        $filters = [];


        return view("transport.shift", compact(
            'select',
            'entityItems',
            'filters',
            "resColumns",
            "resColumnsAll",
            "needMenuForItem",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entityName",
            'urlFilter',
            'orderBy',
            'selectColumn',
            'urlShow'
        ));
    }

    public function update($id){
        $transport = Transport::find($id);
        $newEndShift = is_null($transport->end_shift) ? Carbon::now() : null;
        $newStartShift = !is_null($transport->end_shift) ? Carbon::now() : $transport->start_shift;
        $transport->update(['end_shift' => $newEndShift, 'start_shift' => $newStartShift]);
        return redirect()->back();
    }

    public function create(ShiftCreateRequest $request){
        if(!$request->has('transports')){
            $request['transports'] = [];
        }

        $time = $request->day . ' ' . $request->time;
        $dataCreate = [];

        Shifts::whereDate('start_shift', $request->day)
            ->whereNotIn('transport_id', $request->transports)
            ->delete();

        Shifts::WhereIn('transport_id', $request->transports)
            ->whereDate('start_shift', $request->day)
            ->update(['start_shift'=>$time]);

        $existingTransports = Shifts::WhereIn('transport_id', $request->transports)
            ->whereDate('start_shift', $request->day)
            ->pluck('transport_id')
            ->toArray();


        $missingIds = array_diff($request->transports, $existingTransports);

        foreach($missingIds as $transport_id){
            $dataCreate[] = [
                'transport_id' => $transport_id,
                'start_shift' => $time,
                'end_shift' => null
            ];
        }
        if (!empty($dataCreate)) {
            Shifts::insert($dataCreate);
        }

        return json_encode(['success' => true]);
    }

    public function change(ShiftChangeRequest $request){
        $shift = Shifts::firstOrCreate(
            [
                'transport_id' => $request->id,
                'start_shift' => $request->date . ' ' . '08:00'
            ],
            [
                'end_shift' => null
            ]
        );

        $shift->update(['end_shift' => $shift->end_shift ? null : Carbon::now()]);

        return redirect()->back();
    }
}
