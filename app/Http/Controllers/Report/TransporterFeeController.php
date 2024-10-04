<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Transport;
use DateTime;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TransporterFeeController extends Controller
{
    public function index(Request $request)
    {
        $entityName = 'Перевозчики';

        $month_list = array(
            '01' => 'январь',
            '02' => 'февраль',
            '03' => 'март',
            '04' => 'апрель',
            '05' => 'май',
            '06' => 'июнь',
            '07' => 'июль',
            '08' => 'август',
            '09' => 'сентябрь',
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

        // Transport
        $entityItems = Transport::query()
            ->with('contact')
            ->withSum(['shipments as delivery_fee' => function (Builder $query) use ($date, $dateY) {
                $query
                    ->whereMonth('created_at', $date)
                    ->whereYear('created_at', $dateY);
            }], 'delivery_fee')
            ->groupBy('contact_id')
            ->havingNotNull('contact_id')
            ->get();

        $selected = [
            "link",
            "contact_name",
            'debt',
            'current month',
            'total',
        ];

        foreach ($selected as $column) {
            $resColumnsAll[$column] = ['name_rus' => trans("report." . $column), 'checked' => in_array($column, $selected)];

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("report." . $column);
            }
        }

        return view("report.transporter_fee", compact(
            'entityItems',
            'entityName',
            "resColumns",
            "resColumnsAll",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
        ));
    }
}
