<?php

namespace App\Http\Controllers\Amo;

use App\Filters\Amo\AmoOrderFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Amo\AmoOrderRequest;
use App\Models\ContactAmo;
use App\Models\Manager;
use App\Models\Order;
use App\Models\OrderAmo;
use App\Models\StatusAmo;
use Illuminate\Http\Request;
use DateTime;

class AmoOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:amo_order')->only(['index']);
        $this->middleware('permission:double_order')->only(['doubleOrders']);
    }

    public function index(AmoOrderRequest $request)
    {
        $entityName = 'Заказы АМО';

        // Amo orders
        $builder = OrderAmo::query()->with(['status_amo', 'contact_amo', 'manager']);

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
            'created_at',
            "id",
            "name",
            'status_amo_id',
            'contact_amo_id',
            'price',
            'comment',
            'is_exist',
            'order_link',
            'order_id',
            'updated_at',
            'manager_id',
            'is_success'
        ];

        $select = [
            'created_at',
            "id",
            "name",
            'status_amo_id',
            'contact_amo_id',
            'price',
            'comment',
            'is_exist',
            'order_link',
            'order_id',
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
        $minCreated = OrderAmo::query()->min('created_at');
        $minCreatedCheck = '';
        $maxCreated = OrderAmo::query()->max('created_at');
        $maxCreatedCheck = '';

        $minUpdated = OrderAmo::query()->min('updated_at');
        $minUpdatedCheck = '';
        $maxUpdated = OrderAmo::query()->max('updated_at');
        $maxUpdatedCheck = '';


        $contacts = [];
        $queryIsSuccess = 'index';
        $queryManager = 'index';

        $managers = collect([
            ['value' => 'index', 'name' => 'Все менеджеры']
        ])->merge(
            Manager::all()->map(function ($item) {
                return ['value' => $item->id, 'name' => $item->name];
            })
        )->toArray();

        $statuses = StatusAmo::all()->map(function ($item) {
            return ['value' => $item->id, 'name' => $item->name, 'checked' => true];
        })->toArray();

        if (isset($request->status)) {
            foreach ($statuses as $key => $status) {
                if (!in_array($status['value'], $request->status)) {
                    $statuses[$key]['checked'] = false;
                }
            }
        }


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
                } else if ($key == 'is_success') {
                    $queryIsSuccess = isset($value) ? $value : 'index';
                } else if ($key == 'managers') {
                    $queryManager = isset($value) ? $value : 'index';
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
                'values' => [['value'=>'index', 'name'=>'Все статусы'],['value'=>'1', 'name' => 'Успешно'], ['value'=>'0', 'name' => 'Не успешно']],
                'checked_value' => $queryIsSuccess
            ],
            [
                'type' => 'select2',
                'name' => 'contacts',
                'name_rus' => 'Контакты',
                'values' => $contacts,
            ],
            [
                'type' => 'checkbox',
                'name' => 'status',
                'name_rus' => 'Статусы',
                'values' => $statuses,
                //    'checked_value' => $queryMaterial,
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
            ->with(['contact', 'amo_order' => function ($query) use ($date) {
                $query->whereMonth('created_at', $date)
                ->whereYear('created_at', date('Y'));
            }])
            ->whereHas('amo_order', function ($query) use ($date) {
                $query->whereMonth('created_at', $date)
                ->whereYear('created_at', date('Y'));
            })
            ->withCount(['amo_order' => function ($query) use ($date) {
                $query->whereMonth('created_at', $date)
                ->whereYear('created_at', date('Y'));
            }])
            ->having('amo_order_count', '>', 1)
            ->paginate(100);

        $firstTime = date("Y-$date-01");
        $lastTime = date("Y-$date-t");

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
            'firstTime',
            'lastTime'
        ));
    }
}
