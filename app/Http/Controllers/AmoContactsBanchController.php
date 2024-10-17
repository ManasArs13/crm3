<?php

namespace App\Http\Controllers;

use App\Filters\ContactAmoFilter;
use App\Http\Requests\FilterRequest;
use App\Models\ContactAmoContact;
use App\Models\ContactAmo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class AmoContactsBanchController extends Controller
{
    public function index(FilterRequest $request){
        $urlEdit = "contactAmo.edit";
        $urlShow = "contactAmo.show";
        $urlDelete = "contactAmo.destroy";
        $urlCreate = "contactAmo.create";
        $urlFilter = 'bunch_of_contacts';
        $entity = 'Связка контактов';

        // Contacts Amo
        $builder = ContactAmoContact::query()
            ->with('contact.manager', 'AmoContact.amo_order')
            ->with(['contact.shipments' => function($query) {
                $query->withSum('products', DB::raw('quantity * price'));
            }]);

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
    public function doubleOrders(Request $request){
        $entityName = 'Дубли сделок';

        $month_list = array(
            '01'  => 'январь',
            '02'  => 'февраль',
            '03'  => 'март',
            '04'  => 'апрель',
            '05'  => 'май',
            '06'  => 'июнь',
            '07'  => 'июль',
            '08'  => 'август',
            '09'  => 'сентябрь',
            '10' => 'октябрь',
            '11' => 'ноябрь',
            '12' => 'декабрь'
        );

        if (isset($request->date)) {
            $date = $request->date;
        } else {
            $date = date('m');
        }

        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');


        $entityItems = ContactAmo::query()
            ->with('contact')
            ->withCount('amo_order')
            ->paginate(100);

        $selected = [
            "id",
            "contact_amo_id",
            "count_orders",
        ];

        foreach ($selected as $column) {

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("amo.order.doubles", compact(
            'entityItems',
            'entityName',
            "resColumns",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
        ));
    }
}
