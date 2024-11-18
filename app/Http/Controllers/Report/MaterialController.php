<?php

namespace App\Http\Controllers\report;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;

class MaterialController extends Controller
{
    public function index(Request $request){
        $entityName = 'Журнал материалы';

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

        $dateRus = $month_list[$date];

        $date1 = new DateTime(date('Y') . $date . '01');
        $date2 = new DateTime(date('Y') . $date . '01');

        $datePrev = $date1->modify('-1 month')->format('m');
        $dateNext = $date2->modify('+1 month')->format('m');

        $year = Carbon::now()->year;
        $daysInMonth = Carbon::createFromDate($year, $date)->daysInMonth;
        $startOfMonth = Carbon::createFromDate($year, $date, 1)->startOfMonth()->toDateString();
        $endOfMonth = Carbon::createFromDate($year, $date, 1)->endOfMonth()->toDateString();

        $tables = [
            'incoming',
            'outgoing',
        ];
        $report = [];
        $totals = [];

        $productIds = isset($request->filters['material']) && $request->filters['material'] != 'index' ? [$request->filters['material']] : Product::Where('type', 'материал')->get('id');
        $residual = $this->residual($startOfMonth, $productIds);


        foreach ($tables as $table) {
            if ($table === 'incoming'){

                $suppliesRecords = DB::table('supplies')
                    ->select(
                        DB::raw('DATE(supplies.created_at) as date'),
                        DB::raw('SUM(supply_positions.quantity) as count')
                    )
                    ->join('supply_positions', 'supplies.id', '=', 'supply_positions.supply_id')
                    ->whereIn('supply_positions.product_id', $productIds)
                    ->whereBetween(DB::raw('DATE(supplies.created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(supplies.created_at)'));

                $enterRecords = DB::table('enters')
                    ->select(
                        DB::raw('DATE(enters.created_at) as date'),
                        DB::raw('SUM(enter_positions.quantity) as count')
                    )
                    ->join('enter_positions', 'enters.id', '=', 'enter_positions.enter_id')
                    ->whereIn('enter_positions.product_id', $productIds)
                    ->whereBetween(DB::raw('DATE(enters.created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(enters.created_at)'));

                $records = $suppliesRecords
                    ->union($enterRecords)
                    ->get();
                $records = $records
                    ->groupBy('date')
                    ->map(function ($items, $date) {
                        return (object)[
                            'date' => $date,
                            'count' => $items->sum('count'),
                        ];
                    })
                    ->values();
            } else if ($table === 'outgoing'){
                $techProcessesRecords = DB::table('tech_processes')
                    ->select(
                        DB::raw('DATE(tech_processes.created_at) as date'),
                        DB::raw('SUM(tech_process_materials.quantity) as count')
                    )
                    ->join('tech_process_materials', 'tech_processes.id', '=', 'tech_process_materials.processing_id')
                    ->whereIn('tech_process_materials.product_id', $productIds)
                    ->whereBetween(DB::raw('DATE(tech_processes.created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(tech_processes.created_at)'));

                $shipmentRecords = DB::table('shipments')
                    ->select(
                        DB::raw('DATE(shipments.created_at) as date'),
                        DB::raw('SUM(shipment_products.quantity) as count')
                    )
                    ->join('shipment_products', 'shipments.id', '=', 'shipment_products.shipment_id')
                    ->whereIn('shipment_products.product_id', $productIds)
                    ->whereBetween(DB::raw('DATE(shipments.created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(shipments.created_at)'));

                $lossRecords = DB::table('losses')
                    ->select(
                        DB::raw('DATE(losses.created_at) as date'),
                        DB::raw('SUM(loss_positions.quantity) as count')
                    )
                    ->join('loss_positions', 'losses.id', '=', 'loss_positions.loss_id')
                    ->whereIn('loss_positions.product_id', $productIds)
                    ->whereBetween(DB::raw('DATE(losses.created_at)'), [$startOfMonth, $endOfMonth])
                    ->groupBy(DB::raw('DATE(losses.created_at)'));

                $records = $techProcessesRecords
                    ->union($shipmentRecords)
                    ->union($lossRecords)
                    ->get();
                $records = $records
                    ->groupBy('date')
                    ->map(function ($items, $date) {
                        return (object)[
                            'date' => $date,
                            'count' => $items->sum('count'),
                        ];
                    })
                    ->values();
            } else{
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
        ksort($report);

        $selected = [
            'date',
            'material',
            'incoming',
            'outgoing',
            'residual'
        ];

        foreach ($selected as $column) {

            if (in_array($column, $selected)) {
                $resColumns[$column] = trans("column." . $column);
            }
        }

        $queryMaterial = 'index';
        $materials = Product::Where('type', 'материал')->orderBy('name')->select('id', 'name')->get();
        $materialValues[] = ['value' => 'index', 'name' => 'Все материалы'];
        foreach ($materials as $material) {
            $materialValues[] = ['value' => $material->id, 'name' => $material->name];
        }

        if (isset($request->filters)) {
            foreach ($request->filters as $key => $value) {
                switch ($key) {
                    case 'material':
                        $queryMaterial = $value;
                        break;
                }
            }
        }

        $filters = [
            [
                'type' => 'select',
                'name' => 'material',
                'values' => $materialValues,
                'checked_value' => $queryMaterial,
            ]
        ];

        return view("report.journal_materials", compact(
            'entityName',
            "resColumns",
            "filters",
            'dateNext',
            'datePrev',
            'date',
            'dateRus',
            'tables',
            'report',
            'totals',
            'residual'
        ));
    }


    public function residual($endDate, $productIds){

        // incoming
        $suppliesRecords = DB::table('supplies')
            ->select(
                DB::raw('SUM(supply_positions.quantity) as count')
            )
            ->join('supply_positions', 'supplies.id', '=', 'supply_positions.supply_id')
            ->whereIn('supply_positions.product_id', $productIds)
            ->where(DB::raw('DATE(supplies.created_at)'), '<', $endDate);

        $enterRecords = DB::table('enters')
            ->select(
                DB::raw('SUM(enter_positions.quantity) as count')
            )
            ->join('enter_positions', 'enters.id', '=', 'enter_positions.enter_id')
            ->whereIn('enter_positions.product_id', $productIds)
            ->where(DB::raw('DATE(enters.created_at)'), '<', $endDate);

        // outgoing
        $techProcessesRecords = DB::table('tech_processes')
            ->select(
                DB::raw('SUM(tech_process_materials.quantity) as count')
            )
            ->join('tech_process_materials', 'tech_processes.id', '=', 'tech_process_materials.processing_id')
            ->whereIn('tech_process_materials.product_id', $productIds)
            ->where(DB::raw('DATE(tech_processes.created_at)'), '<', $endDate);

        $shipmentRecords = DB::table('shipments')
            ->select(
                DB::raw('SUM(shipment_products.quantity) as count')
            )
            ->join('shipment_products', 'shipments.id', '=', 'shipment_products.shipment_id')
            ->whereIn('shipment_products.product_id', $productIds)
            ->where(DB::raw('DATE(shipments.created_at)'), '<', $endDate);

        $lossRecords = DB::table('losses')
            ->select(
                DB::raw('SUM(loss_positions.quantity) as count')
            )
            ->join('loss_positions', 'losses.id', '=', 'loss_positions.loss_id')
            ->whereIn('loss_positions.product_id', $productIds)
            ->where(DB::raw('DATE(losses.created_at)'), '<', $endDate);

        $incoming = $suppliesRecords
            ->union($enterRecords)
            ->get();

        $outgoing = $techProcessesRecords
            ->union($shipmentRecords)
            ->union($lossRecords)
            ->get();

        return $incoming->sum('count') - $outgoing->sum('count');
    }
}
