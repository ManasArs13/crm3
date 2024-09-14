<?php

namespace App\Http\Controllers;

use App\Filters\ContactFilter;
use App\Http\Requests\FilterRequest;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ContactController extends Controller
{
    public function index(FilterRequest $request)
    {
        $urlEdit = "contact.edit";
        $urlShow = "contact.show";
        $urlDelete = "contact.destroy";
        $urlCreate = "contact.create";
        $urlFilter = 'contact.filter';
        $entity = 'Контакты МС';

        $builder = Contact::query();

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new ContactFilter($builder, $request))->apply()->orderBy($request->column)->paginate(50);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new ContactFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(50);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new ContactFilter($builder, $request))->apply()->orderBy('id')->paginate(50);
            $selectColumn = null;
        }

        /* Колонки */
        $all_columns = Schema::getColumnListing('contacts');

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

        // Filters
        $minCreated = Contact::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Contact::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Contact::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Contact::query()->max('updated_at');
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

        return view("contact.index", compact(
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
        $entityItem = new Contact();
        $columns = Schema::getColumnListing('contacts');


        $entity = 'contacts';
        $action = "contact.store";

        return view('own.create', compact('entityItem', 'columns', 'action', 'entity'));
    }

    public function store(Request $request)
    {
        $contact = new Contact();

        $contact->name = $request->name;
        $contact->phone = $request->tel;
        $contact->email = $request->mail;

        $contact->save();

        return redirect()->back()->with('succes', 'Контакт ' .$contact->name. ' добавлен');
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
        $columns = $selectedColumns = Schema::getColumnListing('contacts');
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
            'selectedColumns',
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

    public function get_api(Request $request)
    {
        $contacts = Contact::query()
            ->where('name', 'LIKE', '%' . $request->query('term') . '%')
            ->orWhere('id', 'LIKE',  '%' . $request->query('term') . '%')
            ->orWhere('description', 'LIKE',  '%' . $request->query('term') . '%')
            ->orWhere('ms_id', 'LIKE',  '%' . $request->query('term') . '%')
            ->orderByDesc('id')->take(10)->get();

        return response()->json($contacts);
    }
}
