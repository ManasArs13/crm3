<?php

namespace App\Services\Entity;

use App\Models\Order;
use App\Models\OrderPosition;
use App\Models\Product;
use App\Models\Category;
use App\Models\Option;
use App\Models\Shifts;
use App\Models\Shipment;
use App\Models\TechChart;
use App\Models\TechChartProduct;
use App\Models\Transport;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardService
{

    private Carbon $currentDate;
    private $plant_capacity;
    protected array $columns;

    public function __construct()
    {

        $this->currentDate = Carbon::now()->setTime(0, 0);
        $this->columns = [
            "name",
            "contact_id",
            "sostav",
            "sum",
            "date_plan",
            // "status_id",
            "positions_count",
            //'is_demand',
            "residual_count",
            "n",
            "comment",
            "delivery_id",
            //            "ms_link",
        ];

        $this->plant_capacity = Option::select('value')->where('code', 'plant_capacity')->first()?->value;
    }

    public function dashboard($request): View
    {
        $urlShow = "order.show";
        $pageMaterial = 'all';

        if (isset($request->date_plan)) {
            $date = $request->date_plan;
        } else {
            $date = date('Y-m-d');
        }

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $date1 = new DateTime($date);
        $date2 = new DateTime($date);

        $datePrev = $date1->modify('-1 day')->format('Y-m-d');
        $dateNext = $date2->modify('+1 day')->format('Y-m-d');



        $residualWidget = $this->residualWidget();

        $entityItems = Order::query()->with(
            'positions',
            'status',
            'delivery',
            'transport',
            'contact',
            'transport_type',
            'shipments'
        )
            ->whereDate('date_plan', $date)
            ->whereIn('status_id', [3, 4, 5, 6, 7])
            ->orderBy('date_plan')
            ->get();

        $materials = Product::query()->where('type', Product::MATERIAL)
            ->get()
            ->sortBy('sort');

        $shipments = Shipment::whereDate('created_at', $date)
            ->whereHas('products', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE)->orWhere('building_material', Product::BLOCK);
                });
            })
            ->select('id', 'created_at', 'transport_id', 'delivery_id')
            ->with([
                'transport',
                'delivery',
                'transport.shifts' => function ($query) use ($date) {
                    $query->whereDate('start_shift', $date);
                }
            ])->get()
            ->each(function ($shipment) {
                $shipment['time_to_come'] = Carbon::parse($shipment->created_at)->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
                $shipment['time_to_out'] = Carbon::parse($shipment['time_to_come'])->addMinutes(60);
                $shipment['time_to_return'] = Carbon::parse($shipment['time_to_out'])->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
            })
            ->groupBy(function ($shipment) {
                return optional($shipment->transport)->id ?? 'shipment' . $shipment->id;
            })
            ->map(function ($groupedShipments) {
                return $groupedShipments->sortByDesc(function ($shipment) {
                    return $shipment['time_to_return'];
                });
            })
            ->sortBy(function ($groupedShipments) {
                return $groupedShipments->first()['time_to_return'];
            });


        // Количество рейсов текущего месяца
        $allFlights = $this->flightsByDays($startOfMonth, $endOfMonth, $year, $month, 'concrete_or_block');
        $flightsByDaysTransport = $this->flightsByDaysTransport($startOfMonth, $endOfMonth, $year, $month, 'concrete_or_block');


        if ($date > date('Y-m-d')) {

            $orders = Order::query()->with('positions', 'shipments')
                ->whereBetween('date_plan', [date('Y-m-d') . ' 00:00:00', $date . ' 00:00:00'])
                ->whereIn('status_id', [3, 4, 5, 6])
                ->get();

            foreach ($orders as $entityItem) {
                foreach ($entityItem->positions as $order_position) {
                    $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                    if ($techChartProduct) {
                        $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                        foreach ($techCharts as $techChart) {
                            foreach ($techChart->materials as $material) {
                                if ($materials->find($material->id)) {
                                    if (isset($materials->find($material->id)->residual)) {
                                        $materials->find($material->id)->setAttribute('residual', round($materials->find($material->id)->residual - ($material->pivot->quantity * $order_position->quantity), 2));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else if ($date < date('Y-m-d')) {

            $orders = Order::query()->with('positions', 'shipments')
                ->whereBetween('date_plan', [$date . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->whereIn('status_id', [3, 4, 5, 6])
                ->get();

            foreach ($orders as $entityItem) {
                foreach ($entityItem->positions as $order_position) {
                    $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                    if ($techChartProduct) {
                        $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                        foreach ($techCharts as $techChart) {
                            foreach ($techChart->materials as $material) {
                                if ($materials->find($material->id)) {
                                    if (isset($materials->find($material->id)->residual)) {
                                        $materials->find($material->id)->setAttribute('residual', round($materials->find($material->id)->residual + ($material->pivot->quantity * $order_position->quantity), 2));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $orders = $entityItems;
        }

        foreach ($entityItems as $entityItem) {
            foreach ($entityItem->positions as $order_position) {
                $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                if ($techChartProduct) {
                    $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                    foreach ($techCharts as $techChart) {
                        foreach ($techChart->materials as $material) {
                            if ($materials->find($material->id)) {
                                if (isset($materials->find($material->id)->rashod)) {
                                    $materials->find($material->id)->setAttribute('rashod', round($materials->find($material->id)->rashod + ($material->pivot->quantity * $order_position->quantity), 2));
                                } else {
                                    $materials->find($material->id)->setAttribute('rashod', round($material->pivot->quantity * $order_position->quantity, 2));
                                }
                            }
                        }
                    }
                }
            }
        }

        $products = Product::query()->where('type', Product::PRODUCTS)->get()->sortByDesc('sort');
        $entity = 'orders';
        $resColumns = [];

        $categories = Category::query()->where('building_material', Category::BLOCK)->get();

        foreach ($categories as $category) {
            $sum_residual =  Product::query()->where('type', Product::PRODUCTS)->where('category_id', $category->id)->get()->sum('residual');
            $residual_norm = Product::query()->where('type', Product::PRODUCTS)->where('category_id', $category->id)->get()->sum('residual_norm');
            if ($residual_norm !== 0) {
                $remainder = $sum_residual / $residual_norm * 100;
                $category->remainder = round($remainder, 1);
            }
        }

        foreach ($this->columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        $materials = $this->processMaterials($materials);



        $transports = Transport::with(['shifts' => function ($query) use ($date) {
            $query->whereDate('start_shift', $date);
        }])->get();

        $isTransports = [];
        foreach ($shipments as $shipment) {
            if (isset($shipment->first()->transport_id)) {
                $isTransports[] = $shipment->first()->transport_id;
            }
        }
        $shifts = Shifts::WhereDate('start_shift', $date)->whereNotIn('transport_id', $isTransports)->with('transport')->get();

        return view('dashboard.index', compact(
            'urlShow',
            'entityItems',
            "resColumns",
            "entity",
            'products',
            'shipments',
            'categories',
            'materials',
            'dateNext',
            'datePrev',
            'date',
            'transports',
            'shifts',
            'residualWidget',
            'allFlights',
            'pageMaterial',
            'flightsByDaysTransport'
        ));
    }

    public function gettodayOrders(Request $request, $date_plan): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));
        $referer = explode("?", $arUrl[3])[0];
        $referer2 = isset($arUrl[4]) ? explode("?", $arUrl[4])[0] : null;
        $roundPallet = Option::where('code', '=', "round_number")->first()?->value;

        if ($date_plan) {
            $date = $date_plan;
            $day = new DateTime($date_plan);
            $day = $day->format('d-m-Y');
        } else {
            $date = Carbon::now()->format('Y-m-d');
            $day = Carbon::now()->format('d-m-Y');
        }

        $positions_count = [];
        $shipped_count = [];
        $residual_count = [];

        $count = 'quantity';

        $labels = [
            '00:00',
            '08:00',
            '08:30',
            '09:00',
            '09:30',
            '10:00',
            '10:30',
            '11:00',
            '11:30',
            '12:00',
            '12:30',
            '13:00',
            '13:30',
            '14:00',
            '14:30',
            '15:00',
            '15:30',
            '16:00',
            '16:30',
            '17:00',
            '17:30',
            '18:00',
            '18:30',
            '19:00',
            '19:30',
            '20:00',
            '20:30',
            // '21:00',
            // '21:30',
            // '22:00',
            // '23:59'
        ];


        $orders = Order::select('id', 'sum', 'date_plan')->with('positions')
            ->with('positions')
            ->whereIn('status_id', [3, 4, 5, 6])
            ->whereDate('date_plan', $date);

        $shipments = Shipment::select('id', 'created_at')->with('products')
            ->whereDate('created_at', $date);

        if ($referer == 'dashboard' || ($referer == 'summary' && $referer2 == 'all')) {

            $orders = $orders->get();
            $shipments = $shipments->get();
        } else if ($referer == 'dashboard-2' || ($referer == 'summary' && $referer2 == 'block')) {

            $orders = $orders->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK);
                });
            })->get();

            $shipments = $shipments->whereHas('products', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK);
                });
            })->get();

            $count = 'count_pallets';
        } else if ($referer == 'dashboard-3' || ($referer == 'summary' && $referer2 == 'concerte')) {

            $orders = $orders->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })->get();

            $shipments = $shipments->whereHas('products', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })->get();
        }

        foreach ($labels as $key => $label) {
            $positions_count[$key] = 0;
            $shipped_count[$key] = 0;
            $residual_count[$key] = 0;
        }

        foreach ($labels as $key => $label) {

            if ($key !== count($labels) - 1) {
                $nextKey = $key + 1;
                $thisTime = substr($label, 0, -1) . '1';

                $position_count = $orders->whereBetween('date_plan', [$day . ' ' . $thisTime, $day . ' ' . $labels[$nextKey]])->sum(function ($items) use ($count) {
                    $sum = 0;
                    foreach ($items->positions as $position) {
                        if (
                            $position->product->building_material !== 'доставка' &&
                            $position->product->building_material !== 'не выбрано'
                        ) {
                            $sum += $position->$count;
                        }
                    }
                    return $sum;
                });



                if ($referer == 'dashboard-3' || ($referer == 'summary' && $referer2 == 'concerte')) {

                    $orders_whereBetwen = $orders->whereBetween('date_plan', [$day . ' ' . $thisTime, $day . ' ' . $labels[$nextKey]])->all();

                    foreach ($orders_whereBetwen as $order) {

                        $sum = 0;

                        foreach ($order->positions as $position) {

                            if (
                                $position->product->building_material !== 'доставка' &&
                                $position->product->building_material !== 'не выбрано'
                            ) {
                                $sum += $position->quantity;
                            }
                        }

                        if ($sum <= $this->plant_capacity) {
                            $positions_count[$key] += $sum;
                        } else {
                            $count_cycle = (int) ($sum / $this->plant_capacity);

                            for ($i = 0; $i < $count_cycle; $i++) {
                                if (isset($positions_count[$key + $i])) {
                                    $positions_count[$key + $i] += $this->plant_capacity;
                                }
                            }

                            if ($sum % $this->plant_capacity) {
                                if (isset($positions_count[$key + $count_cycle])) {
                                    $positions_count[$key + $count_cycle] += $sum % $this->plant_capacity;
                                }
                            }
                        }
                    }
                } else {
                    $positions_count[$key] += $position_count;
                }
            }

            $shipped_count[$key] += $shipments->whereBetween('created_at', [$day . ' ' . $thisTime, $day . ' ' . $labels[$nextKey]])->sum(function ($items) use ($roundPallet) {
                $sum = 0;
                foreach ($items->products as $product) {
                    if (
                        $product->product->building_material !== 'доставка' &&
                        $product->product->building_material !== 'не выбрано'
                    ) {
                        if ($product->product->count_pallets != 0) {
                            $counts = $product->quantity / $product->product->count_pallets;

                            $quantity_pallets = 0;

                            if ($roundPallet != 0) {
                                $numberNew = round($counts, 2);
                                $drob = $numberNew - floor($numberNew);
                                if ($drob > $roundPallet) {
                                    $quantity_pallets = ceil($numberNew);
                                }
                                $quantity_pallets = floor($numberNew);
                            } else {
                                $quantity_pallets =  $counts;
                            }

                            $sum += $quantity_pallets;
                        } else {
                            $sum += $product->quantity;
                        }
                    }
                }
                return $sum;
            });
            $residual_count[$key] += $positions_count[$key] - $shipped_count[$key];
        }

        array_shift($labels);
        array_pop($labels);

        foreach ($labels as $key => $label) {
            if (substr($label, -2) == 30) {
                $labels[$key] = ' ';
            }
        }

        return response()->json([
            'positions_count' => $positions_count,
            'shipped_count' => $shipped_count,
            'residual_count' => $residual_count,
            'labels' => $labels
        ]);
    }

    public function buildingsMaterialDashboard(Request $request): View
    {
        $arUrl = explode("?", $request->getRequestUri());
        if ($arUrl[0] == '/dashboard-3') {
            return $this->getConcreteOrder($request);
        } else if ($arUrl[0] == '/dashboard-2') {
            return  $this->getBlockOrder($request);
        } else {
            abort(404);
        }
    }

    private function getBlockOrder($request): View
    {
        $urlShow = "order.show";
        $pageMaterial = Product::BLOCK;

        if (isset($request->date_plan)) {
            $date = $request->date_plan;
        } else {
            $date = date('Y-m-d');
        }

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $date1 = new DateTime($date);
        $date2 = new DateTime($date);

        $datePrev = $date1->modify('-1 day')->format('Y-m-d');
        $dateNext = $date2->modify('+1 day')->format('Y-m-d');

        $residualWidget = $this->residualWidget();

        $entityItems = Order::query()->with(
            'positions',
            'status',
            'delivery',
            'transport',
            'contact',
            'transport_type'
        )
            ->whereDate('date_plan', $date)
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK);
                });
            })
            ->whereIn('status_id', [3, 4, 5, 6, 7])
            ->orderBy('date_plan')
            ->get();

        $categories = Category::query()->where('building_material', Category::BLOCK)->get();
        $blocksProducts = Product::query()
            ->where('type', Product::PRODUCTS)
            ->where('building_material', Product::BLOCK)->get()->sortBy('sort');

        $materials = Product::query()
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::BLOCK)
            ->get()
            ->sortBy('sort');

        $shipments = Shipment::whereDate('created_at', $date)
            ->orderBy('created_at')
            ->select('id', 'created_at', 'transport_id', 'delivery_id')
            ->whereHas('products', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK);
                });
            })
            ->with([
                'transport',
                'delivery',
                'transport.shifts' => function ($query) use ($date) {
                    $query->whereDate('start_shift', $date);
                }
            ])->get()
            ->each(function ($shipment) {
                $shipment['time_to_come'] = Carbon::parse($shipment->created_at)->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
                $shipment['time_to_out'] = Carbon::parse($shipment['time_to_come'])->addMinutes(60);
                $shipment['time_to_return'] = Carbon::parse($shipment['time_to_out'])->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
            })
            ->groupBy(function ($shipment) {
                return optional($shipment->transport)->id ?? 'shipment' . $shipment->id;
            })
            ->map(function ($groupedShipments) {
                return $groupedShipments->sortByDesc(function ($shipment) {
                    return $shipment['time_to_return'];
                });
            })
            ->sortBy(function ($groupedShipments) {
                return $groupedShipments->first()['time_to_return'];
            });

        // Количество рейсов текущего месяца
        $allFlights = $this->flightsByDays($startOfMonth, $endOfMonth, $year, $month, 'block');
        $flightsByDaysTransport = $this->flightsByDaysTransport($startOfMonth, $endOfMonth, $year, $month, 'block');


        if ($date > date('Y-m-d')) {

            $orders = Order::query()->with('positions')
                ->whereBetween('date_plan', [date('Y-m-d') . ' 00:00:00', $date . ' 00:00:00'])
                ->whereIn('status_id', [3, 4, 5, 6])
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                })
                ->get();

            foreach ($orders as $entityItem) {
                foreach ($entityItem->positions as $order_position) {
                    $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                    if ($techChartProduct) {
                        $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                        foreach ($techCharts as $techChart) {
                            foreach ($techChart->materials as $material) {
                                if ($materials->find($material->id)) {
                                    if (isset($materials->find($material->id)->residual)) {
                                        $materials->find($material->id)->setAttribute('residual', round($materials->find($material->id)->residual - ($material->pivot->quantity * $order_position->quantity), 2));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else if ($date < date('Y-m-d')) {

            $orders = Order::query()->with('positions')
                ->whereBetween('date_plan', [$date . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->whereIn('status_id', [3, 4, 5, 6])
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK);
                    });
                })
                ->get();

            foreach ($orders as $entityItem) {
                foreach ($entityItem->positions as $order_position) {
                    $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                    if ($techChartProduct) {
                        $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                        foreach ($techCharts as $techChart) {
                            foreach ($techChart->materials as $material) {
                                if ($materials->find($material->id)) {
                                    if (isset($materials->find($material->id)->residual)) {
                                        $materials->find($material->id)->setAttribute('residual', round($materials->find($material->id)->residual + ($material->pivot->quantity * $order_position->quantity), 2));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $orders = $entityItems;
        }

        foreach ($entityItems as $entityItem) {
            foreach ($entityItem->positions as $order_position) {
                $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                if ($techChartProduct) {
                    $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                    foreach ($techCharts as $techChart) {
                        foreach ($techChart->materials as $material) {
                            if ($materials->find($material->id)) {
                                if (isset($materials->find($material->id)->rashod)) {
                                    $materials->find($material->id)->setAttribute('rashod', round($materials->find($material->id)->rashod + ($material->pivot->quantity * $order_position->quantity), 2));
                                } else {
                                    $materials->find($material->id)->setAttribute('rashod', round($material->pivot->quantity * $order_position->quantity, 2));
                                }
                            }
                        }
                    }
                }
            }
        }

        $entity = 'orders';

        foreach ($categories as $category) {
            $sum_residual =  Product::query()->where('type', Product::PRODUCTS)->where('category_id', $category->id)->get()->sum('residual');
            $residual_norm = Product::query()->where('type', Product::PRODUCTS)->where('category_id', $category->id)->get()->sum('residual_norm');
            if ($residual_norm !== 0) {
                $remainder = $sum_residual / $residual_norm * 100;
                $category->remainder = round($remainder, 1);
            }
        }

        $resColumns = [];
        foreach ($this->columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }


        $materials = $this->processMaterials($materials);

        $transports = Transport::with(['shifts' => function ($query) use ($date) {
            $query->whereDate('start_shift', $date);
        }])->get();

        $isTransports = [];
        foreach ($shipments as $shipment) {
            if (isset($shipment->first()->transport_id)) {
                $isTransports[] = $shipment->first()->transport_id;
            }
        }
        $shifts = Shifts::WhereDate('start_shift', $date)->whereNotIn('transport_id', $isTransports)->with('transport')->get();



        return view('dashboard.block', compact(
            'urlShow',
            'entityItems',
            "resColumns",
            "entity",
            'materials',
            'blocksProducts',
            'categories',
            'dateNext',
            'datePrev',
            'date',
            'shipments',
            'transports',
            'shifts',
            'residualWidget',
            'allFlights',
            'pageMaterial',
            'flightsByDaysTransport'
        ));
    }

    private function getConcreteOrder($request): View
    {
        $urlShow = "order.show";
        $pageMaterial = Product::CONCRETE;

        if (isset($request->date_plan)) {
            $date = $request->date_plan;
        } else {
            $date = date('Y-m-d');
        }

        $year = date('Y', strtotime($date));
        $month = date('m', strtotime($date));
        $startOfMonth = Carbon::create($year, $month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $date1 = new DateTime($date);
        $date2 = new DateTime($date);

        $datePrev = $date1->modify('-1 day')->format('Y-m-d');
        $dateNext = $date2->modify('+1 day')->format('Y-m-d');

        $residual = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_positions', 'products.id', '=', 'order_positions.product_id')
            ->join('orders', 'order_positions.order_id', '=', 'orders.id')
            ->where(function ($query) {
                $query->Where('categories.building_material', Category::CONCRETE);
            })
            ->where('products.release', '!=', 0)
            ->where('orders.status_id', '=', 4)
            ->selectRaw('SUM(DISTINCT products.residual / products.release) as residual')
            ->first()->residual;

        $orderCount = DB::table('categories')
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_positions', 'products.id', '=', 'order_positions.product_id')
            ->join('orders', 'order_positions.order_id', '=', 'orders.id')
            ->where(function ($query) {
                $query->Where('categories.building_material', Category::CONCRETE);
            })
            ->where('orders.status_id', 4)
            ->where('products.type', Product::PRODUCTS)
            ->selectRaw('
                products.id,
                SUM(order_positions.quantity) as total_quantity,
                COUNT(DISTINCT order_positions.order_id) as unique_orders
            ')
            ->groupBy('products.id')
            ->get()
            ->sum(fn($product) => $product->unique_orders > 0
                ? $product->total_quantity / $product->unique_orders
                : 0
            );

        $entityItems = Order::query()->with(
            'positions',
            'status',
            'delivery',
            'transport',
            'contact',
            'transport_type',
            'shipments'
        )
            ->whereDate('date_plan', $date)
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->whereIn('status_id', [3, 4, 5, 6, 7])
            ->orderBy('date_plan')
            ->get();


        $materials = Product::query()
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::CONCRETE)
            ->get()
            ->sortBy('sort');

        $shipments = Shipment::whereDate('created_at', $date)
            ->whereHas('products', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->select('id', 'created_at', 'transport_id', 'delivery_id')
            ->with([
                'transport',
                'delivery',
                'transport.shifts' => function ($query) use ($date) {
                    $query->whereDate('start_shift', $date);
                }
            ])->get()
            ->each(function ($shipment) {
                $shipment['time_to_come'] = Carbon::parse($shipment->created_at)->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
                $shipment['time_to_out'] = Carbon::parse($shipment['time_to_come'])->addMinutes(60);
                $shipment['time_to_return'] = Carbon::parse($shipment['time_to_out'])->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
            })
            ->groupBy(function ($shipment) {
                return optional($shipment->transport)->id ?? 'shipment' . $shipment->id;
            })
            ->map(function ($groupedShipments) {
                return $groupedShipments->sortByDesc(function ($shipment) {
                    return $shipment['time_to_return'];
                });
            })
            ->sortBy(function ($groupedShipments) {
                return $groupedShipments->first()['time_to_return'];
            });


        // Количество рейсов текущего месяца
        $allFlights = $this->flightsByDays($startOfMonth, $endOfMonth, $year, $month, 'concrete');
        $flightsByDaysTransport = $this->flightsByDaysTransport($startOfMonth, $endOfMonth, $year, $month, 'concrete');


        if ($date > date('Y-m-d')) {

            $orders = Order::query()->with('positions')
                ->whereBetween('date_plan', [date('Y-m-d') . ' 00:00:00', $date . ' 00:00:00'])
                ->whereIn('status_id', [3, 4, 5, 6])
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                })
                ->get();

            foreach ($orders as $entityItem) {
                foreach ($entityItem->positions as $order_position) {
                    $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                    if ($techChartProduct) {
                        $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                        foreach ($techCharts as $techChart) {
                            foreach ($techChart->materials as $material) {
                                if ($materials->find($material->id)) {
                                    if (isset($materials->find($material->id)->residual)) {
                                        $materials->find($material->id)->setAttribute('residual', round($materials->find($material->id)->residual - ($material->pivot->quantity * $order_position->quantity), 2));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else if ($date < date('Y-m-d')) {

            $orders = Order::query()->with('positions')
                ->whereBetween('date_plan', [$date . ' 00:00:00', date('Y-m-d') . ' 00:00:00'])
                ->whereIn('status_id', [3, 4, 5, 6])
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE);
                    });
                })
                ->get();

            foreach ($orders as $entityItem) {
                foreach ($entityItem->positions as $order_position) {
                    $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                    if ($techChartProduct) {
                        $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                        foreach ($techCharts as $techChart) {
                            foreach ($techChart->materials as $material) {
                                if ($materials->find($material->id)) {
                                    if (isset($materials->find($material->id)->residual)) {
                                        $materials->find($material->id)->setAttribute('residual', round($materials->find($material->id)->residual + ($material->pivot->quantity * $order_position->quantity), 2));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $orders = $entityItems;
        }

        foreach ($entityItems as $entityItem) {
            foreach ($entityItem->positions as $order_position) {
                $techChartProduct = TechChartProduct::select('id', 'tech_chart_id')->where('product_id', $order_position->product_id)->First();
                if ($techChartProduct) {
                    $techCharts = TechChart::with('materials')->where('id', $techChartProduct->tech_chart_id)->get();
                    foreach ($techCharts as $techChart) {
                        foreach ($techChart->materials as $material) {
                            if ($materials->find($material->id)) {
                                if (isset($materials->find($material->id)->rashod)) {
                                    $materials->find($material->id)->setAttribute('rashod', round($materials->find($material->id)->rashod + ($material->pivot->quantity * $order_position->quantity), 2));
                                } else {
                                    $materials->find($material->id)->setAttribute('rashod', round($material->pivot->quantity * $order_position->quantity, 2));
                                }
                            }
                        }
                    }
                }
            }
        }

        $entity = 'orders';

        $resColumns = [];
        foreach ($this->columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        $materials = $this->processMaterials($materials);

        $transports = Transport::with(['shifts' => function ($query) use ($date) {
            $query->whereDate('start_shift', $date);
        }])->get();

        $isTransports = [];
        foreach ($shipments as $shipment) {
            if (isset($shipment->first()->transport_id)) {
                $isTransports[] = $shipment->first()->transport_id;
            }
        }
        $shifts = Shifts::WhereDate('start_shift', $date)->whereNotIn('transport_id', $isTransports)->with('transport')->get();

        return view('dashboard.concrete', compact(
            'urlShow',
            'entityItems',
            "resColumns",
            "entity",
            'materials',
            'dateNext',
            'datePrev',
            'date',
            'shipments',
            'transports',
            'shifts',
            'residual',
            'orderCount',
            'allFlights',
            'pageMaterial',
            'flightsByDaysTransport'
        ));
    }

    public function processMaterials($materials)
    {
        $groupedMaterials = collect();

        foreach ($materials as $material) {

            if (str_contains($material->short_name, 'Краска')) {
                $index = $groupedMaterials->search(function ($item) {
                    return str_contains($item['short_name'], 'Краска');
                });

                if ($index !== false) {
                    $existingMaterial = $groupedMaterials[$index];
                    $existingMaterial['residual'] += $material->residual;
                    $existingMaterial['residual_norm'] += $material->residual_norm;
                    $existingMaterial['rashod'] += $material->rashod;
                    $groupedMaterials[$index] = $existingMaterial;
                } else {
                    $groupedMaterials->push([
                        'id' => $material->id,
                        'short_name' => 'Краска',
                        'residual' => $material->residual,
                        'residual_norm' => $material->residual_norm,
                        'rashod' => $material->rashod,
                    ]);
                }
            } else {
                $groupedMaterials->push([
                    'id' => $material->id,
                    'short_name' => $material->short_name,
                    'residual' => $material->residual,
                    'residual_norm' => $material->residual_norm,
                    'rashod' => $material->rashod,
                ]);
            }
        }
        return $groupedMaterials;
    }

    public function getOrderDataForMap(): JsonResponse
    {
        $orderDates = Order::query()->whereDate('date_plan', '>=', $this->currentDate)->get();
        foreach ($orderDates as $date) {
            $date->load('delivery',);
        }
        return response()->json(['orderDates' => $orderDates]);
    }

    public function roundNumber($number, $round = 0.2)
    {
        if ($round != 0) {
            $numberNew = round($number, 2);
            $drob = $numberNew - floor($numberNew);
            if ($drob > $round) {
                return ceil($numberNew);
            }
            return floor($numberNew);
        } else {
            return $number;
        }
    }

    public function residualWidget(){


        $products = Category::query()
            ->where('building_material', Category::BLOCK)
            ->orwhere('building_material', Category::CONCRETE)
            ->get();

        $orderCount = 0;
        $residual = 0;

        foreach ($products as $product) {
            $product->pre_products = Product::query()
                ->where('type', Product::PRODUCTS)
                ->where('category_id', $product->id)
                ->get();
            $product->residual_norm = Product::query()
                ->where('type', Product::PRODUCTS)
                ->where('category_id', $product->id)
                ->get()
                ->sum('residual_norm');

            $product_ids = $product->pre_products->pluck('id');

            $product->totalOrderQuantity = OrderPosition::whereIn('product_id', $product_ids)
                ->whereHas('order', function ($query) {
                    $query->where('status_id', 4);
                })
                ->sum('quantity');
            $product->totalOrderSum = OrderPosition::whereIn('product_id', $product_ids)
                ->distinct('order_id')
                ->whereHas('order', function ($query) {
                    $query->where('status_id', 4);
                })->count('order_id');

            $preProductOrders = OrderPosition::whereIn('product_id', $product_ids)
                ->whereHas('order', function ($query) {
                    $query->where('status_id', 4);
                })
                ->selectRaw('product_id, SUM(quantity) as totalOrderQuantity')
                ->groupBy('product_id')
                ->get()
                ->keyBy('product_id');
            if($product->residual_norm){

                foreach ($product->pre_products as $preProduct) {
                    $residual += isset($preProduct->residual) && isset($preProduct->release) ? $preProduct->residual / $preProduct->release : 0;
                    $orderCount +=
                        isset($preProductOrders[$preProduct->id]->totalOrderQuantity)
                        && isset($preProduct->release)
                        && $preProductOrders[$preProduct->id]->totalOrderQuantity != 0
                        && $preProduct->release != 0
                            ? $preProductOrders[$preProduct->id]->totalOrderQuantity / $preProduct->release : 0;
                }
            }
        }

        return ['residual' => $residual, 'orderCount' => $orderCount ];
    }

    public function flightsByDays($startOfMonth, $endOfMonth, $year, $month, $materialFilter){
        $allFlights = [];
        $startOfMonth = clone $startOfMonth;
        $endOfMonth = clone $endOfMonth;

        while ($startOfMonth <= $endOfMonth) {
            $allFlights[$startOfMonth->format('Y-m-d')] = [
                'day' => $startOfMonth->format('d'),
                'shipments_count' => 0,
                'routes_count' => 0,
            ];
            $startOfMonth->addDay();
        }

        $shipmentsMount = Shipment::selectRaw('DATE(created_at) as day, COUNT(*) as shipments_count, COUNT(DISTINCT transport_id) as routes_count')
            ->whereHas('products', function ($query) use ($materialFilter) {
                $query->whereHas('product', function ($queries) use ($materialFilter) {
                    switch ($materialFilter) {
                        case 'concrete_or_block':
                            $queries->where('building_material', Product::CONCRETE)
                                ->orWhere('building_material', Product::BLOCK);
                            break;

                        case 'concrete':
                            $queries->where('building_material', Product::CONCRETE);
                            break;

                        case 'block':
                            $queries->where('building_material', Product::BLOCK);
                            break;
                    }
                });
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        foreach ($shipmentsMount as $row) {
            $allFlights[$row->day] = [
                'day' => date('d', strtotime($row->day)),
                'shipments_count' => $row->shipments_count,
                'routes_count' => $row->routes_count,
            ];
        }
        return $allFlights;
    }

    public function flightsByDaysTransport($startOfMonth, $endOfMonth, $year, $month, $materialFilter){
        $allFlights = [];
        $startOfMonth = clone $startOfMonth;
        $endOfMonth = clone $endOfMonth;


        while ($startOfMonth <= $endOfMonth) {
            $allFlights[$startOfMonth->format('Y-m-d')] = [
                'day' => $startOfMonth->format('d'),
                'transports' => [],
            ];
            $startOfMonth->addDay();
        }

        $shipmentsMount = Shipment::selectRaw('DATE(created_at) as day, transport_id, COUNT(*) as flights_count')
            ->with('transport:name,id')
            ->whereHas('products', function ($query) use ($materialFilter) {
                $query->whereHas('product', function ($queries) use ($materialFilter) {
                    switch ($materialFilter) {
                        case 'concrete_or_block':
                            $queries->where('building_material', Product::CONCRETE)
                                ->orWhere('building_material', Product::BLOCK);
                            break;

                        case 'concrete':
                            $queries->where('building_material', Product::CONCRETE);
                            break;

                        case 'block':
                            $queries->where('building_material', Product::BLOCK);
                            break;
                    }
                });
            })
            ->whereHas('transport', function($query){
                $query->where('main', 1);
            })
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->groupBy(DB::raw('DATE(created_at)'), 'transport_id')
            ->get();


        $transports = [];
        $transportNames = [];

        foreach ($shipmentsMount as $row) {
            $day = $row->day;
            $transport = $row->transport;

            if ($transport) {
                $transportName = $transport->name;
                $transportId = $row->transport_id;


                if (!isset($transportNames[$transportId])) {
                    $transportNames[$transportId] = $transportName;
                }

                if (!isset($transports[$transportName])) {
                    $transports[$transportName] = array_fill_keys(array_keys($allFlights), 0);
                }

                $transports[$transportName][$day] = $row->flights_count;
            }
        }

        return [
            'days' => array_keys($allFlights),
            'transports' => $transports,
        ];
    }
}
