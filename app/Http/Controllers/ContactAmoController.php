<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Models\ContactAmo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContactAmoController extends Controller
{
    public function index(FilterRequest $request)
    {
        $entityItems = ContactAmo::query();
        $needMenuForItem = true;
        $urlEdit = "contact.edit";
        $urlShow = "contact.show";
        $urlDelete = "contact.destroy";
        $urlCreate = "contact.create";
        $urlFilter = 'contact.filter';
        $entity = 'contacts';
        $orderBy  = $request->orderBy;
        $selectColumn = $request->column;

        /* Сортировка */
        if (isset($request->orderBy)  && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
        } else if (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems = $entityItems->paginate(50);
        }

        /* Колонки */
        $columns = Schema::getColumnListing('contacts');
        $resColumns = [];
        $resColumnsAll = [];

        foreach ($columns as $column) {
            $resColumns[$column] = trans("column." . $column);
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => false];
        }

        /* Фильтры для меню */
        $minCreated = ContactAmo::query()->min('created_at');
        $maxCreated = ContactAmo::query()->max('created_at');
        $minUpdated = ContactAmo::query()->min('updated_at');
        $maxUpdated = ContactAmo::query()->max('updated_at');
        
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
            'filters',
            'orderBy',
            'selectColumn'
        ));
    }

    public function create()
    {
        $entityItem = new ContactAmo();
        $columns = Schema::getColumnListing('contacts');


        $entity = 'contacts';
        $action = "contact.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        $contact = new ContactAmo();

        $contact->name = $request->name;
        $contact->phone = $request->tel;
        $contact->email = $request->mail;

        $contact->save();

        return redirect()->back()->with('succes', 'Контакт ' .$contact->name. ' добавлен');
    }

    public function show(string $id)
    {
        $entityItem = ContactAmo::findOrFail($id);
        $columns = Schema::getColumnListing('contacts');
        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id)
    {
        $entityItem = ContactAmo::find($id);
        $columns = Schema::getColumnListing('contacts');
        $entity = 'contacts';
        $action = "contact.update";

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = ContactAmo::find($id);
        $entityItem->fill($request->post())->save();

        return redirect()->route('contact.index');
    }

    public function destroy(string $id)
    {
        $entityItem = ContactAmo::find($id);
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
        $entityItems = ContactAmo::query();

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
        $minCreated = ContactAmo::query()->min('created_at');
        $maxCreated = ContactAmo::query()->max('created_at');
        $minUpdated = ContactAmo::query()->min('updated_at');
        $maxUpdated = ContactAmo::query()->max('updated_at');
        
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
