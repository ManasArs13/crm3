<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContactController extends Controller
{
    public function index()
    {
        $entityItems = Contact::query()->paginate(50);
        $needMenuForItem = true;
        $urlEdit = "contact.edit";
        $urlShow = "contact.show";
        $urlDelete = "contact.destroy";
        $urlCreate = "contact.create";
        $urlFilter = 'contact.filter';
        $entity = 'contacts';

        /* Колонки */
        $columns = Schema::getColumnListing('contacts');
        $resColumns = [];
        $resColumnsAll = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        /* Фильтры для меню */
        $minCreated = Contact::query()->min('created_at');
        $maxCreated = Contact::query()->max('created_at');
        $minUpdated = Contact::query()->min('updated_at');
        $maxUpdated = Contact::query()->max('updated_at');
        
        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'max' => substr($maxCreated, 0, 10),
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'max' => substr($maxUpdated, 0, 10)
            ],
        ];

        return view("own.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "needMenuForItem",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'urlFilter',
            'filters'
        ));
    }

    public function create()
    {
        $entityItem = new Contact();
        $columns = Schema::getColumnListing('contacts');


        $entity = 'contacts';
        $action = "contact.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        Contact::create($request->post());
        return redirect()->route("contact.index");
    }

    public function show(string $id)
    {
        $entityItem = Contact::findOrFail($id);
        $columns = Schema::getColumnListing('contacts');
        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = Contact::find($id);
        $columns = Schema::getColumnListing('contacts');
        $entity = 'contacts';
        $action = "contact.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = Contact::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('contact.index');
    }

    public function destroy(string $id)
    {
        $entityItem = Contact::find($id);
        $entityItem->delete();

        return redirect()->route('contact.index');
    }
    public function filter(FilterRequest $request)
    {
        $needMenuForItem = true;
        $urlEdit = "contact.edit";
        $urlShow = "contact.show";
        $urlDelete = "contact.destroy";
        $urlCreate = "contact.create";
        $urlFilter = 'contact.filter';
        $urlReset = 'contact.index';
        $entity = 'contacts';
        $selectColumn = $request->column;
        $entityItems = Contact::query();

        /* Колонки */
        $columns = Schema::getColumnListing('contacts');
        $resColumns = [];
        $resColumnsAll = [];

        /* Колонки для меню */
        foreach ($columns as $column) {
            $resColumnsAll[$column] = [
                'name_rus' => trans("column." . $column),
                'checked' => in_array($column, $request->columns ? $request->columns : []) ? true : false
            ];
        }

        /* Колонки для отображения */
        if (isset($request->columns)) {
            $requestColumns = $request->columns;
            $requestColumns[] = "id";
            $columns = $requestColumns;
            $entityItems = $entityItems->select($requestColumns);
        }

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        /* Фильтры для отображения */
        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                if ($key == 'created_at' || $key == 'updated_at') {
                    $entityItems
                        ->where($key, '>=', $value['min'] . ' 00:00:00')
                        ->where($key, '<=', $value['max'] . ' 23:59:59');
                }
            }
        }

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

        /* Фильтры для меню */
        $minCreated = Contact::query()->min('created_at');
        $maxCreated = Contact::query()->max('created_at');
        $minUpdated = Contact::query()->min('updated_at');
        $maxUpdated = Contact::query()->max('updated_at');
        
        $filters = [
            [
                'type' => 'date',
                'name' =>  'created_at',
                'name_rus' => 'Дата создания',
                'min' => substr($minCreated, 0, 10),
                'max' => substr($maxCreated, 0, 10),
            ],
            [
                'type' => 'date',
                'name' =>  'updated_at',
                'name_rus' => 'Дата обновления',
                'min' => substr($minUpdated, 0, 10),
                'max' => substr($maxUpdated, 0, 10)
            ],
        ];

        return view("own.index", compact(
            'entityItems',
            "resColumns",
            "resColumnsAll",
            "needMenuForItem",
            "urlShow",
            "urlDelete",
            "urlEdit",
            "urlCreate",
            "entity",
            'urlFilter',
            'urlReset',
            'orderBy',
            'filters',
            'selectColumn'
        ));
    }
}
