<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Cashin;
use App\Models\Cashout;
use App\Models\Contact;
use App\Models\Product;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;


class CashController extends Controller
{
    public function index(Request $request)
    {
        $entityName = 'Сводка - Платежи';

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

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        $cashin = Cashin::query()
            ->with('contact')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY);

        $cashout = Cashout::query()
            ->with('contact')
            ->whereMonth('moment', $date)
            ->whereYear('moment', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $cashin = $cashin->orderBy($request->column);
            $cashout = $cashout->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $cashin = $cashin->orderByDesc($request->column);
            $cashout = $cashout->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $cashin = $cashin->orderByDesc('id');
            $cashout = $cashout->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $cashin = $cashin->get();
        $cashout = $cashout->get();

        $entityItems = $cashin->merge($cashout);

        $selected = [
            "name",
            "operation",
            "moment",
            'description',
            "contact_id",
            "sum",
            "created_at",
            'updated_at',
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }
        return view("report.cash", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
            'orderBy',
            'selectColumn'
        ));
    }

    public function cashin(Request $request)
    {
        $entityName = 'Сводка - Платежи';

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

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        $cashin = Cashin::query()
            ->with('contact')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $cashin = $cashin->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $cashin = $cashin->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $cashin = $cashin->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $cashin->paginate(50);

        $selected = [
            "name",
            "operation",
            "moment",
            'description',
            "contact_id",
            "sum",
            "created_at",
            'updated_at',
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.cash", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
            'orderBy',
            'selectColumn'
        ));
    }

    public function cashout(Request $request)
    {
        $entityName = 'Сводка - Платежи';

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

        $dateY = date('Y');
        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        $cashout = Cashout::query()
            ->with('contact')
            ->whereMonth('moment', $date)
            ->whereYear('moment', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $cashout = $cashout->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $cashout = $cashout->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $cashout = $cashout->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $cashout->paginate(50);

        $selected = [
            "name",
            "operation",
            "moment",
            'description',
            "contact_id",
            "sum",
            "created_at",
            'updated_at',
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("column." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.cash", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
            'orderBy',
            'selectColumn'
        ));
    }
}
