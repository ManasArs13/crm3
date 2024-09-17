<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\Transport\TransportStoreRequest;
use App\Http\Requests\Transport\TransportUpdateRequest;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class TransportController extends Controller
{
    public function index(FilterRequest $request)
    {
        $entityItems = Transport::query();
        $needMenuForItem = true;
        $urlEdit = "transport.edit";
        $urlDelete = "transport.destroy";
        $urlCreate = "transport.create";
        $urlFilter = 'transport.index';
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
        ];


        $select = [
            "id",
            "name",
            'description',
            'tonnage',
            'contact_id',
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

        return view("transport.index", compact(
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
            'selectColumn'
        ));
    }

    public function create()
    {
        $entity = 'Транспорт';
        $action = "transport.store";

        $searchContacts = "api.get.contact";

        return view('transport.create', compact('action', 'entity', 'searchContacts'));
    }

    public function store(TransportStoreRequest $request)
    {
        $validated = $request->validated();

        $transport = new Transport($validated);

        $transport->save();

        return redirect()->route("transport.index")
            ->with('success', "Транспорт $transport->name добавлен");
    }

    public function show(string $id)
    {
        return redirect()->route('transport.edit', ['transport' => $id]);
    }

    public function edit(string $id)
    {
        $transport = Transport::find($id);

        if (!$transport) {
            return redirect()->route("transport.index")
                ->with('warning', "Транспорт не найден");
        }
        $entity = "Транспорт $transport->name";
        $action = "transport.update";
        $searchContacts = "api.get.contact";

        return view("transport.edit", compact('transport', 'entity', 'action', 'searchContacts'));
    }

    public function update(TransportUpdateRequest $request, string $id)
    {
        $validated = $request->validated();

        $transport = Transport::find($id);

        if (!$transport) {
            return redirect()->route("transport.index")
                ->with('warning', "Транспорт не найден");
        }

        $transport->update($validated);

        $transport->save();

        return redirect()->route("transport.edit", ['transport' => $transport->id])
            ->with('success', "Транспорт $transport->name обнавлён");
    }

    public function destroy(string $id)
    {
        $transport = Transport::find($id);

        if (!$transport) {
            return redirect()->route("transport.index")
                ->with('warning', "Транспорт не найден");
        }

        $deletedName = $transport->name;
        $transport->delete();

        return redirect()->route("transport.index")
            ->with('success', "Транспорт $deletedName удалён");
    }
}
