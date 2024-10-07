<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use DateTime;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $entityName = 'Платежи (Все)';

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

        $entityItems = Payment::query()
            ->with('contact')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $entityItems = $entityItems->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $entityItems->paginate(50);

        $selected = [
            "name",
            "type",
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
        return view("finance.payment", compact(
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
        $entityName = 'Платежи (Приход)';

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

        $entityItems = Payment::query()
            ->where('type', 'cashin')
            ->with('contact')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $entityItems = $entityItems->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $entityItems->paginate(50);

        $selected = [
            "name",
            "type",
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
        return view("finance.payment", compact(
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
        $entityName = 'Платежи (Расход)';

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

        $entityItems = Payment::query()
            ->where('type', 'cashout')
            ->with('contact')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $entityItems = $entityItems->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $entityItems->paginate(50);

        $selected = [
            "name",
            "type",
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
        return view("finance.payment", compact(
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

    public function paymentin(Request $request)
    {
        $entityName = 'Платежи (Входящие)';

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

        $entityItems = Payment::query()
            ->where('type', 'paymentin')
            ->with('contact')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $entityItems = $entityItems->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $entityItems->paginate(50);

        $selected = [
            "name",
            "type",
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
        return view("finance.payment", compact(
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

    public function paymentout(Request $request)
    {
        $entityName = 'Платежи (Входящие)';

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

        $entityItems = Payment::query()
            ->where('type', 'paymentout')
            ->with('contact')
            ->whereMonth('created_at', $date)
            ->whereYear('created_at', $dateY);

        if (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'asc') {
            $entityItems = $entityItems->orderBy($request->column);

            $orderBy = 'desc';
            $selectColumn = $request->column;
        } elseif (isset($request->column) && isset($request->orderBy) && $request->orderBy == 'desc') {
            $entityItems = $entityItems->orderByDesc($request->column);

            $orderBy = 'asc';
            $selectColumn = $request->column;
        } else {
            $entityItems = $entityItems->orderByDesc('id');

            $orderBy = 'desc';
            $selectColumn = null;
        }

        $entityItems = $entityItems->paginate(50);

        $selected = [
            "name",
            "type",
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
        return view("finance.payment", compact(
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
