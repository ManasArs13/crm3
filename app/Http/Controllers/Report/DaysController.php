<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\ContactAmo;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DaysController extends Controller
{
    public function index(Request $request){
        $entityName = 'Сводка по дням';

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

        $year = Carbon::now()->year;
        $daysInMonth = Carbon::createFromDate($year, $date)->daysInMonth;
        $startOfMonth = Carbon::createFromDate($year, $date, 1)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromDate($year, $date, 1)->endOfMonth()->toDateString();

        $tables = ['order_amos', 'contact_amos', 'shipments', 'contacts', 'sum_shipments'];
        $report = [];
        $totals = [];

        foreach ($tables as $table) {
            if($table === 'sum_shipments'){
                $records = DB::table('shipments')
                    ->select(
                        DB::raw('DATE(shipments.created_at) as date'),
                        DB::raw('COUNT(shipments.id) as count'),
                        DB::raw('SUM(shipment_products.quantity * shipment_products.price) as count')
                    )
                    ->join('shipment_products', 'shipments.id', '=', 'shipment_products.shipment_id')
                    ->whereBetween(DB::raw('DATE(shipments.created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(shipments.created_at)'))
                    ->get();

            }else{
                $records = DB::table($table)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
                    ->whereBetween(DB::raw('DATE(created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(created_at)'))
                    ->get();

            }

            foreach ($records as $record) {
                $report[$record->date][$table] = $record->count;
            }
        }


        for ($i = 0; $i < $daysInMonth; $i++) {
            $day = Carbon::createFromDate($year, $date, 1)->startOfMonth()->addDays($i)->toDateString();

            if (!isset($report[$day])) {
                $report[$day] = [];
            }

            foreach ($tables as $table) {
                if (!isset($report[$day][$table])) {
                    $report[$day][$table] = 0;
                }

                $totals[$table] ?? $totals[$table] = 0;
                $totals[$table] += $report[$day][$table];
            }
        }


        $selected = [
            "date",
            "amo_orders",
            "contacts_amo",
            "count_shipments",
            "sum_shipments",
            "contacts_ms",
        ];

        foreach ($selected as $column) {

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        return view("report.days", compact(
            'entityName',
            "resColumns",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
            'tables',
            'report',
            'totals'
        ));
    }
}
