<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;


class ShipmentController extends Controller
{

    public function getShipmentsByMonthAndCategory()
    {
        $date = Carbon::now()->format('Y-m-d');
        $labels = [
            '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12',
        ];
        $charts = [];
        $year = Carbon::now()->format('Y');
        $datasets = [];

        // Материалы
        $datasets = array_merge($datasets, $this->getMaterialsData($year, $labels, $charts));

        // сделки амо
        $datasets = array_merge($datasets, $this->getOrderData($year, $labels));

        // Закрытые заказы
        $datasets = array_merge($datasets, $this->getClosedOrdersData($year, $labels));

        // Успешные заказы
        $datasets = array_merge($datasets, $this->getCompletedOrdersData($year, $labels));

        // техпроцессы
        $datasets = array_merge($datasets, $this->getTechProcessesData($year, $labels));

        // Входящие звонки
        $datasets = array_merge($datasets, $this->getCallsData($year, $labels));

        // Исходящие звонки
        $datasets = array_merge($datasets, $this->getOutgoingCallsData($year, $labels));

        // Беседы
        $datasets = array_merge($datasets, $this->getUniqueContactsData($year, $labels));

        return response()->json([
            'charts' => $charts,
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }


    private function getMaterialColor($material)
    {
        if($material == 'блок'){
            return '#86EFAC';
        } elseif($material == 'бетон'){
            return '#FFCD56';
        }
        return '#FCA5A5';
    }

    private function getMaterialsData($year, $labels, &$charts)
    {
        $datasets = [];
        $dbResults = Shipment::selectRaw('building_material as material,
        DATE_FORMAT(`shipments`.created_at, "%m") as month1,
        sum(shipment_products.price*shipment_products.quantity) as sum1')
            ->join('shipment_products','shipments.id','=','shipment_products.shipment_id')
            ->join('products','products.id','=','shipment_products.product_id')
            ->whereNotNull('building_material')->where('building_material','<>', Product::NOT_SELECTED)
            ->where('type','=', Product::PRODUCTS)
            ->where('shipments.created_at','>=', $year.'-01-01 00:00:00')
            ->groupBy('month1',"material")->orderBy("month1")->get();

        foreach ($dbResults as $dbRow) {
            $color = $this->getMaterialColor($dbRow->material);

            if (!isset($charts[$dbRow->material])) {
                $charts[$dbRow->material] = array_fill_keys($labels, null); // Заполняем null для всех месяцев
            }

            $charts[$dbRow->material][$dbRow->month1] = $dbRow->sum1;

            $datasets[$dbRow->material] = [
                "label" => $dbRow->material,
                "hidden" => false,
                "data" => array_values($charts[$dbRow->material]),
                "backgroundColor" => $color,
                "borderColor" => $color,
                "borderWidth" => 4
            ];
        }

        return $datasets;
    }


    private function getOrderData($year, $labels)
    {
        $orderAmos = DB::table('order_amos')
            ->selectRaw('DATE_FORMAT(created_at, "%m") as month, COUNT(*) as count')
            ->where('is_success', 1)
            ->where('created_at','>=', $year.'-01-01 00:00:00')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%m")'))
            ->orderBy('month')
            ->get();

        $orderCharts = array_fill_keys($labels, null);

        foreach ($orderAmos as $record) {
            $orderCharts[$record->month] = $record->count;
        }

        return [
            'Orders' => [
                "label" => "Сделок амо",
                "hidden" => true,
                "data" => array_values($orderCharts),
                "backgroundColor" => "#60A5FA",
                "borderColor" => "#3B82F6",
                "borderWidth" => 4,
            ]
        ];
    }


    private function getClosedOrdersData($year, $labels)
    {
        $closedOrderRecords = DB::table('order_amos')
            ->selectRaw(
                'DATE_FORMAT(closed_at, "%m") as month,
        COUNT(*) as count'
            )
            ->where('is_success', 1)
            ->where('status_amo_id', 143)
            ->where('closed_at','>=', $year.'-01-01 00:00:00')
            ->groupBy(DB::raw('DATE_FORMAT(closed_at, "%m")'))
            ->orderBy('month')
            ->get();

        $closedOrderCharts = array_fill_keys($labels, null);

        foreach ($closedOrderRecords as $record) {
            $closedOrderCharts[$record->month] = $record->count;
        }

        return [
            'ClosedOrders' => [
                "label" => "Закрыто сделок",
                "hidden" => true,
                "data" => array_values($closedOrderCharts),
                "backgroundColor" => "#A78BFA",
                "borderColor" => "#7C3AED",
                "borderWidth" => 4,
            ]
        ];
    }


    private function getCompletedOrdersData($year, $labels)
    {
        $completedOrderRecords = DB::table('order_amos')
            ->selectRaw(
                'DATE_FORMAT(closed_at, "%m") as month,
        COUNT(*) as count'
            )
            ->where('is_success', 1)
            ->where('status_amo_id', 142)
            ->where('closed_at','>=', $year.'-01-01 00:00:00')
            ->groupBy(DB::raw('DATE_FORMAT(closed_at, "%m")'))
            ->orderBy('month')
            ->get();

        $completedOrderCharts = array_fill_keys($labels, null);

        foreach ($completedOrderRecords as $record) {
            $completedOrderCharts[$record->month] = $record->count;
        }

        return [
            'CompletedOrders' => [
                "label" => "Успешных сделок",
                "hidden" => true,
                "data" => array_values($completedOrderCharts),
                "backgroundColor" => "#34D399",
                "borderColor" => "#059669",
                "borderWidth" => 4,
            ]
        ];
    }


    private function getTechProcessesData($year, $labels)
    {
        $techProcessRecords = DB::table('tech_process_products')
            ->join('products', 'tech_process_products.product_id', '=', 'products.id')
            ->join('tech_processes', 'tech_process_products.processing_id', '=', 'tech_processes.id')
            ->selectRaw(
                'DATE_FORMAT(tech_processes.moment, "%m") as month,
        SUM(tech_process_products.quantity / products.pieces_cycle) as count'
            )
            ->where('tech_processes.moment','>=', $year.'-01-01 00:00:00')
            ->groupBy(DB::raw('DATE_FORMAT(tech_processes.moment, "%m")'))
            ->orderBy('month')
            ->get();

        $techProcessCharts = array_fill_keys($labels, null);

        foreach ($techProcessRecords as $record) {
            $techProcessCharts[$record->month] = $record->count;
        }

        return [
            'TechProcesses' => [
                "label" => "Циклы",
                "hidden" => true,
                "data" => array_values($techProcessCharts),
                "backgroundColor" => "#60A5FA",
                "borderColor" => "#2563EB",
                "borderWidth" => 4,
            ]
        ];
    }


    private function getCallsData($year, $labels)
    {
        $callRecords = DB::table('calls')
            ->selectRaw(
                'DATE_FORMAT(created_at, "%m") as month,
        COUNT(*) as count'
            )
            ->where('created_at','>=', $year.'-01-01 00:00:00')
            ->where('type', 'incoming_call')
            ->where('duration', '>', 0)
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%m")'))
            ->orderBy('month')
            ->get();

        $callCharts = array_fill_keys($labels, null);

        foreach ($callRecords as $record) {
            $callCharts[$record->month] = $record->count;
        }

        return [
            'Calls' => [
                "label" => "Входящие звонки",
                "hidden" => true,
                "data" => array_values($callCharts),
                "backgroundColor" => "#F87171",
                "borderColor" => "#DC2626",
                "borderWidth" => 4,
            ]
        ];
    }


    private function getOutgoingCallsData($year, $labels)
    {
        $outgoingCallRecords = DB::table('calls')
            ->selectRaw(
                'DATE_FORMAT(created_at, "%m") as month,
        COUNT(*) as count'
            )
            ->where('created_at','>=', $year.'-01-01 00:00:00')
            ->where('type', 'outgoing_call')
            ->where('duration', '>', 0)
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%m")'))
            ->orderBy('month')
            ->get();

        $outgoingCallCharts = array_fill_keys($labels, null);

        foreach ($outgoingCallRecords as $record) {
            $outgoingCallCharts[$record->month] = $record->count;
        }

        return [
            'OutgoingCalls' => [
                "label" => "Исходящие звонки",
                "hidden" => true,
                "data" => array_values($outgoingCallCharts),
                "backgroundColor" => "#60A5FA",
                "borderColor" => "#2563EB",
                "borderWidth" => 4,
            ]
        ];
    }


    private function getUniqueContactsData($year, $labels)
    {
        $uniqueContactsRecords = DB::table('talk_amos')
            ->selectRaw('DATE_FORMAT(created_at, "%m") as month, COUNT(DISTINCT contact_amo_id) as count')
            ->where('created_at','>=', $year.'-01-01 00:00:00')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%m")'))
            ->orderBy('month')
            ->get();

        $uniqueContactsCharts = array_fill_keys($labels, null);

        foreach ($uniqueContactsRecords as $record) {
            $uniqueContactsCharts[$record->month] = $record->count;
        }

        return [
            'UniqueContacts' => [
                "label" => "Беседы",
                "hidden" => true,
                "data" => array_values($uniqueContactsCharts),
                "backgroundColor" => "#FBBF24",
                "borderColor" => "#D97706",
                "borderWidth" => 4,
            ]
        ];
    }

}
