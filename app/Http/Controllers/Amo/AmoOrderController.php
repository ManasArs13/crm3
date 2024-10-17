<?php

namespace App\Http\Controllers\Amo;

use App\Filters\Amo\AmoOrderFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Amo\AmoOrderRequest;
use App\Models\ContactAmo;
use App\Models\Order;
use App\Models\OrderAmo;
use Illuminate\Http\Request;
use DateTime;

class AmoOrderController extends Controller
{
    public function index(AmoOrderRequest $request)
    {
        $entityName = 'Заказы АМО';

        // Amo orders
        $builder = OrderAmo::query()->with(['status_amo', 'contact_amo']);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = (new AmoOrderFilter($builder, $request))->apply()->orderBy($request->column)->paginate(100);
            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = (new AmoOrderFilter($builder, $request))->apply()->orderByDesc($request->column)->paginate(100);
            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $orderBy = 'desc';
            $entityItems = (new AmoOrderFilter($builder, $request))->apply()->orderByDesc('id')->paginate(100);
            $selectColumn = null;
        }

        $all_columns = [
            "id",
            "name",
            'status_amo_id',
            'contact_amo_id',
            'price',
            'comment',
            'is_exist',
            'order_link',
            'order_id',
            'created_at',
            'updated_at',
            'manager_id',
            'is_success'
        ];

        $select = [
            "id",
            "name",
            'status_amo_id',
            'contact_amo_id',
            'price',
            'comment',
            'is_exist',
            'order_link',
            'order_id',
            'created_at',
            'updated_at',
            'manager_id',
            'is_success'
        ];

        $selected = $request->columns ?? $select;

        foreach ($all_columns as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        // Filters
        $minCreated = Order::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = Order::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = Order::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = Order::query()->max('updated_at');
        $maxUpdatedCheck = '';

        $contacts = [];



        $queryMaterial = 'index';
        $queryPlan = 'today';

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
                } else if ($key == 'contacts') {
                    $contact_names_get = ContactAmo::WhereIn('id', $value)->get(['id', 'phone']);

                    if (isset($value)) {
                        $contacts = [];
                        foreach ($contact_names_get as $val){
                            $contacts[] = [
                                'value' => $val->id,
                                'name' => $val->phone
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
                'type' => 'select2',
                'name' => 'contacts',
                'name_rus' => 'Контакты',
                'values' => $contacts,
            ],
        ];

        return view('amo.order.index', compact(
            "resColumns",
            "resColumnsAll",
            'entityName',
            'entityItems',
            'orderBy',
            'selectColumn',
            'filters',
            'select'
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
            ->whereHas('amo_order', function ($query) use ($date) {
                $query->whereMonth('created_at', $date)
                ->whereYear('created_at', date('Y'));
            })
            ->having('amo_order_count', '>', 1)
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
