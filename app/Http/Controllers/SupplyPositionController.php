<?php

namespace App\Http\Controllers;

use App\Filters\SupplyPositionFilter;
use App\Http\Requests\FilterRequest;
use App\Models\Contact;
use App\Models\Product;
use App\Models\SupplyPosition;
use Illuminate\Http\Request;

class SupplyPositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:supply_position')->only(['index']);
        $this->middleware('permission:supply_position_edit')->only(['create','store', 'edit', 'update', 'destroy']);
    }

    public function index(FilterRequest $request){
        $urlEdit = "supply_positions.edit";
        $urlShow = "supply_positions.show";
        $urlDelete = "supply_positions.destroy";
        $urlCreate = "supply_positions.create";
        $urlFilter = 'supply_positions.index';
        $entity = 'supplies_position';
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

        // Supply-product
        $builder = SupplyPosition::query()->with('supply', 'products');

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new SupplyPositionFilter($builder, $request))->apply()->orderBy($request->column);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new SupplyPositionFilter($builder, $request))->apply()->orderByDesc($request->column);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new SupplyPositionFilter($builder, $request))->apply()->orderBy('id');
            $selectColumn = null;
        }

        // итоги в таблицах
        $totals = $this->total($entityItems);

        $entityItems = $entityItems->paginate(100);

        // Колонки
        $all_columns = [
            'id',
            'supply_id',
            'quantity',
            'product_id',
            'created_at',
            'updated_at',
            'ms_id',
            'price',
        ];

        if (isset($request->columns)) {
            $selected = $request->columns;
        } else {
            $selected = $all_columns;
        }

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        /* Фильтры для меню */
        $contacts = [];
        $queryMaterial = 'index';
        $materials = Product::Where('type', 'материал')->orderBy('name')->select('id', 'name')->get();
        $materialValues[] = ['value' => 'index', 'name' => 'Все'];
        foreach ($materials as $material) {
            $materialValues[] = ['value' => $material->id, 'name' => $material->name];
        }

        $minCreated = SupplyPosition::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = SupplyPosition::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = SupplyPosition::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = SupplyPosition::query()->max('updated_at');
        $maxUpdatedCheck = '';

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at') {
                    if ($value['max']) {
                        $maxCreatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minCreatedCheck = $value['min'];
                    }
                }
                if ($key == 'material'){
                    $queryMaterial = $value;
                }
                if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                }
                if($key == 'contacts'){
                    $contact_names_get = Contact::WhereIn('id', $value)->get(['id', 'name']);
                    if (isset($value)) {
                        $contacts = [];
                        foreach ($contact_names_get as $val){
                            $contacts[] = [
                                'value' => $val->id,
                                'name' => $val->name
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
                'type' => 'select',
                'name' => 'material',
                'name_rus' => 'Материал',
                'values' => $materialValues,
                'checked_value' => $queryMaterial,
            ],
            [
                'type' => 'select2',
                'name' => 'contacts',
                'name_rus' => 'Контакты',
                'values' => $contacts,
            ],
        ];



        return view("supply.products", compact(
            'all_columns',
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'filters',
            'urlFilter',
            'orderBy',
            'selectColumn',
            'totals'
        ));
    }

    public function create(){

    }

    public function store(){

    }

    public function edit(){

    }

    public function update(){

    }

    public function destroy(string $id)
    {
        $entityItem = SupplyPosition::find($id);
        $entityItem->delete();

        return redirect()->back();
    }

    public function total($entityItems){
        return [
            'total_price' => $entityItems->sum('price'),
        ];
    }

}
