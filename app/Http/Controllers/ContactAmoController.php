<?php

namespace App\Http\Controllers;

use App\Filters\ContactAmoFilter;
use App\Http\Requests\FilterRequest;
use App\Models\ContactAmo;
use App\Models\Manager;
use App\Models\Errors;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContactAmoController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:amo_contact')->only(['index', 'filter']);
        $this->middleware('permission:amo_contact_edit')->only(['create','store', 'show', 'edit', 'update', 'destroy']);
    }

    public function index(FilterRequest $request)
    {
        $urlEdit = "contactAmo.edit";
        $urlShow = "contactAmo.show";
        $urlDelete = "contactAmo.destroy";
        $urlCreate = "contactAmo.create";
        $urlFilter = 'contactAmo.index';
        $entity = 'Контакты АМО';

        // Contacts Amo
        $builder = ContactAmo::query();

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ContactAmoFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ContactAmoFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ContactAmoFilter($builder, $request))->apply()->orderBy('id')->paginate(100);
            $selectColumn = null;
        }

        // Columns
        $all_columns = [
            'id',
            'name',
            'phone',
            'phone1',
            'email',
            'phone_norm',
            'contact_ms_id',
            'contact_ms_link',
            'is_exist',
            'is_dublash',
            'created_at',
            'updated_at',
            'is_success'
        ];

        $select = [
            'id',
            'name',
            'phone',
            'phone_norm',
            'contact_ms_link',
            'is_exist',
            'is_dublash',
            'created_at',
            'updated_at',
            'is_success'
        ];

        $selected = $request->columns ?? $select;


        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        list($minCreatedCheck, $maxCreatedCheck, $minUpdatedCheck, $maxUpdatedCheck) = '';
        // Filters
        $minCreated = ContactAmo::query()->min('created_at');
        $maxCreated = ContactAmo::query()->max('created_at');
        $minUpdated = ContactAmo::query()->min('updated_at');
        $maxUpdated = ContactAmo::query()->max('updated_at');

        $queryManager = 'index';
        $queryIsSuccess = 'index';
        $managers = Manager::all()->map(function ($item) {
                        return ['value' => $item->id, 'name' => $item->name];
                    });

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
                if ($key == 'updated_at') {
                    if ($value['max']) {
                        $maxUpdatedCheck = $value['max'];
                    }
                    if ($value['min']) {
                        $minUpdatedCheck = $value['min'];
                    }
                }
                else if ($key == 'managers') {
                    $queryManager = isset($value) ? $value : 'all';
                }
                else if ($key == 'is_success') {
                    $queryIsSuccess = isset($value) ? $value : 'all';
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
                'name' => 'managers',
                'name_rus' => 'Менеджеры',
                'values' => $managers,
                'checked_value' => $queryManager
            ],
            [
                'type' => 'select',
                'name' => 'is_success',
                'name_rus' => 'Успешная сделка',
                'values' => [['value'=>'1', 'name' => 'Успешно'], ['value'=>'0', 'name' => 'Не успешно']],
                'checked_value' => $queryIsSuccess
            ],
        ];

        return view("contact.amo", compact(
            'all_columns',
            'entityItems',
            "resColumns",
            "resColumnsAll",
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

        return redirect()->back()->with('succes', 'Контакт ' . $contact->name . ' добавлен');
    }

    public function show(string $id)
    {
        $entityItem = ContactAmo::findOrFail($id);
        $columns = Schema::getColumnListing('contacts');
        return view("own.show", compact('entityItem', 'columns'));
    }

    public function edit(string $id, Request $request)
    {
        $entityItem = ContactAmo::find($id);
        $columns = Schema::getColumnListing('contact_amos');
        $entity = 'contact_amos';
        $action = "contactAmo.update";
        $error = null;
        if (isset($request->error_fix)) {
            $errorRecord = Errors::find($request->error_fix);

            if ($errorRecord && $errorRecord->responsible_user == Auth::id()) {
                $error = $errorRecord;
            }
        }

        return view("own.edit", compact('entityItem', 'columns', 'action', 'entity', 'error'));
    }

    public function update(Request $request, string $id)
    {
        $entityItem = ContactAmo::find($id);
        if(!$entityItem){
            abort(404);
        }
        $entityItem->fill($request->post())->save();

        if (isset($request->error_fix) && $request->has('responsible_description')) {
            $errorRecord = Errors::find($request->error_fix);

            if ($errorRecord && $errorRecord->responsible_user == Auth::id()) {
                $errorRecord->user_description = $request->responsible_description;
                $errorRecord->save();
            }
        }

        return redirect()->back()->with('success', "Контакт №{$entityItem->id} успешно изменен");
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
            $entityItems = $entityItems->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
        } elseif (isset($request->orderBy)  && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
        } else {
            $orderBy = 'desc';
            $entityItems =   $entityItems->paginate(100);
        }

        /* Фильтры для меню */
        $minCreated = ContactAmo::query()->min('created_at');
        $maxCreated = ContactAmo::query()->max('created_at');
        $minUpdated = ContactAmo::query()->min('updated_at');
        $maxUpdated = ContactAmo::query()->max('updated_at');

        list($minCreatedCheck, $maxCreatedCheck, $minUpdatedCheck, $maxUpdatedCheck) = '';

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
                if ($key == 'updated_at') {
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
                'maxChecked' => $maxCreatedCheck,
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
