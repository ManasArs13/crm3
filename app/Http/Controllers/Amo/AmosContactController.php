<?php

namespace App\Http\Controllers\Amo;

use App\Filters\ContactAmoFilter;
use App\Http\Requests\FilterRequest;
use App\Models\ContactAmoContact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AmosContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:contact_link')->only(['index']);
    }

    public function index(FilterRequest $request){
        $urlEdit = "contactAmo.edit";
        $urlShow = "contactAmo.show";
        $urlDelete = "contactAmo.destroy";
        $urlCreate = "contactAmo.create";
        $urlFilter = 'bunch_of_contacts';
        $entity = 'Связка контактов';

        // Contacts Amo
        $builder = ContactAmoContact::query()
            ->with('contact.manager', 'AmoContact.amo_order', 'AmoContact.manager')
            ->with(['contact.shipments' => function($query) {
                $query->withSum('products', DB::raw('quantity * price'));
            }])
            ->addSelect([
                'contact_amo_contacts.*',
                'created_at_ms' => DB::table('contacts')
                    ->select('created_at')
                    ->whereColumn('contacts.id', 'contact_amo_contacts.contact_id')
                    ->limit(1),
                'created_at_amo' => DB::table('contact_amos')
                    ->select('created_at')
                    ->whereColumn('contact_amos.id', 'contact_amo_contacts.contact_amo_id')
                    ->limit(1),
                'shipment_id_ms' => DB::table('shipments')
                    ->join('shipment_products', 'shipments.id', '=', 'shipment_products.shipment_id')
                    ->selectRaw('SUM(shipment_products.quantity * shipment_products.price)')
                    ->whereColumn('shipments.contact_id', 'contact_amo_contacts.contact_id')
                    ->limit(1),
                'shipment_id_amo' => DB::table('shipments')
                    ->join('shipment_products', 'shipments.id', '=', 'shipment_products.shipment_id')
                    ->selectRaw('SUM(shipment_products.quantity * shipment_products.price)')
                    ->whereColumn('shipments.contact_id', 'contact_amo_contacts.contact_id')
                    ->limit(1),
                'shipment_id_amo' => DB::table('order_amos')
                    ->selectRaw('SUM(price)')
                    ->whereColumn('order_amos.contact_amo_id', 'contact_amo_contacts.contact_amo_id')
                    ->limit(1),
                'manager_id_ms' => DB::table('contacts')
                    ->select('created_at')
                    ->whereColumn('contacts.id', 'contact_amo_contacts.contact_id')
                    ->limit(1),
            ]);

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
            'created_at',
            'contact_id',
            'contact_amo_id',
            'created_at_ms',
            'created_at_amo',
            'shipment_id_ms',
            'shipment_id_amo',
            'manager_id_ms',
            'manager_id_amo',
        ];

        $select = [
            'created_at',
            'contact_id',
            'contact_amo_id',
            'created_at_ms',
            'created_at_amo',
            'shipment_id_ms',
            'shipment_id_amo',
            'manager_id_ms',
            'manager_id_amo',
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
        $minCreated = ContactAmoContact::query()->min('created_at');
        $maxCreated = ContactAmoContact::query()->max('created_at');
        $minUpdated = ContactAmoContact::query()->min('updated_at');
        $maxUpdated = ContactAmoContact::query()->max('updated_at');

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

        return view("contact.bunch", compact(
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
}
