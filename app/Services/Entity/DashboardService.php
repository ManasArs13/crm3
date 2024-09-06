<?php

namespace App\Services\Entity;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Shipment;
use App\Models\TechChart;
use App\Models\TechChartProduct;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardService
{

    private Carbon $currentDate;
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
//            "status_id",
            "positions_count",
            'is_demand',
            "residual_count",
            "comment",
            "delivery_id",
            "ms_link",
        ];
    }

    public function dashboard($request): View
    {
        $urlShow = "order.show";

        if (isset($request->date_plan)) {
            $date = $request->date_plan;
        } else {
            $date = date('Y-m-d');
        }

        $date1 = new DateTime($date);
        $date2 = new DateTime($date);

        $datePrev = $date1->modify('-1 day')->format('Y-m-d');
        $dateNext = $date2->modify('+1 day')->format('Y-m-d');

        $entityItems = Order::query()->with(
            'positions',
            'status',
            'delivery',
            'transport',
            'contact',
            'transport_type'
        )
            ->whereDate('date_plan', $date)
            ->whereIn('status_id', [3, 4, 5, 6])
            ->orderBy('date_plan')
            ->get();

        $materials = Product::query()->where('type', Product::MATERIAL)
            ->get()
            ->sortBy('sort');

        if ($date > date('Y-m-d')) {

            $orders = Order::query()->with('positions')
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

            $orders = Order::query()->with('positions')
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

        foreach ($this->columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        return view('dashboard.index', compact(
            'urlShow',
            'entityItems',
            "resColumns",
            "entity",
            'products',
            'materials',
            'dateNext',
            'datePrev',
            'date',
        ));
    }

    public function gettodayOrders(Request $request, $date_plan): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));
        $referer = explode("?", $arUrl[3])[0];

        if ($date_plan) {
            $date = $date_plan;
            $day = new DateTime($date_plan);
            $day = $day->format('d-m-Y');
        } else {
            $date = Carbon::now()->format('Y-m-d');
            $day = Carbon::now()->format('d-m-Y');
        }

        $sum = [];
        $positions_count = [];
        $shipped_count = [];
        $orders_count = [];
        $labels = [
            '08',
            '09',
            '10',
            '11',
            '12',
            '13',
            '14',
            '15',
            '16',
            '17',
            '18',
            '19',
            '20',
            '21',
            '22'
        ];

        $orders = Order::select('id', 'sum', 'date_plan')->withCount('positions')
            ->with('positions')
            ->whereIn('status_id', [3, 4, 5, 6])
            ->whereDate('date_plan', $date);

        $shipments = Shipment::select('id', 'created_at')->with('products')
            ->whereDate('created_at', $date);

        if ($referer == 'dashboard') {

            $orders = $orders->get();
            $shipments = $shipments->get();
        } else if ($referer == 'dashboard-2') {

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
        } else if ($referer == 'dashboard-3') {

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

        for ($i = 0; $i < 16; $i++) {
            switch ($i) {
                case 0:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 00:00', $day . ' 08:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 00:00', $day . ' 08:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 00:00', $day . ' 08:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 00:00', $day . ' 08:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 1:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 09:00', $day . ' 09:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 09:00', $day . ' 09:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 09:00', $day . ' 09:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 09:00', $day . ' 09:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 2:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 10:00', $day . ' 10:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 10:00', $day . ' 10:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 10:00', $day . ' 10:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 10:00', $day . ' 10:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 3:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 11:00', $day . ' 11:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 11:00', $day . ' 11:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 11:00', $day . ' 11:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 11:00', $day . ' 11:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 4:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 12:00', $day . ' 12:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 12:00', $day . ' 12:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 12:00', $day . ' 12:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 12:00', $day . ' 12:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 5:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 13:00', $day . ' 13:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 13:00', $day . ' 13:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 13:00', $day . ' 13:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 13:00', $day . ' 13:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 6:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 14:00', $day . ' 14:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 14:00', $day . ' 14:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 14:00', $day . ' 14:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 14:00', $day . ' 14:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 7:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 15:00', $day . ' 15:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 15:00', $day . ' 15:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 15:00', $day . ' 15:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 15:00', $day . ' 15:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 9:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 16:00', $day . ' 16:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 16:00', $day . ' 16:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 16:00', $day . ' 16:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 16:00', $day . ' 16:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 10:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 17:00', $day . ' 17:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 17:00', $day . ' 17:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 17:00', $day . ' 17:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 17:00', $day . ' 17:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 11:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 18:00', $day . ' 18:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 18:00', $day . ' 18:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 18:00', $day . ' 18:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 18:00', $day . ' 18:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 12:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 19:00', $day . ' 19:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 19:00', $day . ' 19:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 19:00', $day . ' 19:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 19:00', $day . ' 19:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 13:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 20:00', $day . ' 20:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 20:00', $day . ' 20:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 20:00', $day . ' 20:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 20:00', $day . ' 20:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 14:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 21:00', $day . ' 21:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 21:00', $day . ' 21:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 21:00', $day . ' 21:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 21:00', $day . ' 21:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
                case 15:
                    $sum[] = $orders->whereBetween('date_plan', [$day . ' 22:00', $day . ' 23:59'])->sum('sum');
                    $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 22:00', $day . ' 23:59'])->count('positions_count');
                    $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 22:00', $day . ' 23:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->positions as $position) {
                            $sum += $position->quantity;
                        }
                        return $sum;
                    });
                    $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 22:00', $day . ' 23:59'])->sum(function ($items) {
                        $sum = 0;
                        foreach ($items->products as $product) {
                            $sum += $product->quantity;
                        }
                        return $sum;
                    });
                    break;
            };
        }

        return response()->json([
            'sum' => $sum,
            'orders_count' => $orders_count,
            'positions_count' => $positions_count,
            'shipped_count' => $shipped_count,
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

        if (isset($request->date_plan)) {
            $date = $request->date_plan;
        } else {
            $date = date('Y-m-d');
        }

        $date1 = new DateTime($date);
        $date2 = new DateTime($date);

        $datePrev = $date1->modify('-1 day')->format('Y-m-d');
        $dateNext = $date2->modify('+1 day')->format('Y-m-d');

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
            ->whereIn('status_id', [3, 4, 5, 6])
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
            ->with('transport', 'delivery')
            ->get();

        foreach ($shipments as $shipment) {
            $shipment['time_to_come'] = Carbon::parse(Carbon::parse($shipment->created_at)->format('H:i'))->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
            $shipment['time_to_out'] = Carbon::parse(Carbon::parse($shipment->time_to_come)->format('H:i'))->addMinutes(60);
            $shipment['time_to_return'] = Carbon::parse(Carbon::parse($shipment['time_to_out'])->format('H:i'))->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
        }

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
            'shipments'
        ));
    }

    private function getConcreteOrder($request): View
    {
        $urlShow = "order.show";

        if (isset($request->date_plan)) {
            $date = $request->date_plan;
        } else {
            $date = date('Y-m-d');
        }

        $date1 = new DateTime($date);
        $date2 = new DateTime($date);

        $datePrev = $date1->modify('-1 day')->format('Y-m-d');
        $dateNext = $date2->modify('+1 day')->format('Y-m-d');

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
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->whereIn('status_id', [3, 4, 5, 6])
            ->orderBy('date_plan')
            ->get();

        $materials = Product::query()
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::CONCRETE)
            ->get()
            ->sortBy('sort');

        $shipments = Shipment::whereDate('created_at', $date)
            ->orderBy('created_at')
            ->whereHas('products', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->select('id', 'created_at', 'transport_id', 'delivery_id')
            ->with('transport', 'delivery')
            ->get();

        foreach ($shipments as $shipment) {
            $shipment['time_to_come'] = Carbon::parse(Carbon::parse($shipment->created_at)->format('H:i'))->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
            $shipment['time_to_out'] = Carbon::parse(Carbon::parse($shipment->time_to_come)->format('H:i'))->addMinutes(60);
            $shipment['time_to_return'] = Carbon::parse(Carbon::parse($shipment['time_to_out'])->format('H:i'))->addMinutes($shipment->delivery ? $shipment->delivery->time_minute : 0);
        }

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

        return view('dashboard.concrete', compact(
            'urlShow',
            'entityItems',
            "resColumns",
            "entity",
            'materials',
            'dateNext',
            'datePrev',
            'date',
            'shipments'
        ));
    }

    public function getOrderDataForMap(): JsonResponse
    {
        $orderDates = Order::query()->whereDate('date_plan', '>=', $this->currentDate)->get();
        foreach ($orderDates as $date) {
            $date->load('delivery',);
        }
        return response()->json(['orderDates' => $orderDates]);
    }
}
