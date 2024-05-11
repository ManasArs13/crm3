<?php

namespace App\Services\Entity;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderPosition;
use App\Models\Shipment;
use App\Models\TechChart;
use App\Models\TechChartProduct;
use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
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
            'status_shipped',
            "sum",
            "date_plan",
            "status_id",
            "comment",
            "positions_count",
            "residual_count",
            "delivery_id",
            "ms_link",
        ];
    }

    /**
     * @param $request
     * @return View
     */
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

        $entityItems = Order::query()->with('positions')
            ->whereDate('date_plan', $date)
            ->orderBy('date_plan')
            ->get();
        $this->loadRelations($entityItems);

        $materials = Product::query()->where('type', Product::MATERIAL)
            ->get()
            ->sortBy('sort');

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

        return view('Dashboard.index', compact(
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

    /**
     * @param $request
     * @return JsonResponse
     */
    public function fetchOrders($request): JsonResponse
    {
        $selectedDate = $request->input('date');
        $orders = [];

        if ($request->filter == 'concrete') {

            $orders = Order::query()->whereDate('date_plan', $selectedDate)
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE)->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                    });
                })

                ->orderBy('date_plan')
                ->get();
        } else if ($request->filter == 'block') {

            $orders = Order::query()->whereDate('date_plan', $selectedDate)
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK)->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                    });
                })

                ->orderBy('date_plan')
                ->get();
        } else if ($request->filter == 'index') {

            $orders = Order::query()->whereDate('date_plan', $selectedDate)
                ->orderBy('date_plan')
                ->get();
        }

        $this->loadRelations($orders);

        return response()->json(['entityItems' => $orders]);
    }

    /**
     * @param $request
     * @return JsonResponse
     */
    public function getOrderMonth($request): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));
        $referer = explode("?", $arUrl[3])[0];
        $nextTenDaysEnd = Carbon::now()->addDays(10);
        $currentDates = clone $this->currentDate;

        $orders = Order::query()->with('positions');
        $orders2 = [];

        $dates = [];
        $orderData = [];
        $counts = [];
        $labels = [1, 2, 3];

        while ($nextTenDaysEnd >= $currentDates) {
            $dates[$currentDates->format('Y-m-d')] = 0;
            $orderData[$currentDates->format('Y-m-d')] = 0;
            $currentDates->addDay();
        }

        if ($referer == 'dashboard') {

            $orders = Order::query()->with('positions')
                ->whereDate('date_plan', '>=', $this->currentDate)
                ->whereDate('date_plan', '<=', $nextTenDaysEnd)
                ->orderBy('date_plan')
                ->get();
            $orders2 = Order::query()
                ->whereDate('date_plan', '>=', $this->currentDate)
                ->whereDate('date_plan', '<=', $nextTenDaysEnd)
                ->orderBy('date_plan')
                ->get();
        } else if ($referer == 'dashboard-2') {

            $orders = Order::query()->with('positions')
                ->whereDate('date_plan', '>=', $this->currentDate)
                ->whereDate('date_plan', '<=', $nextTenDaysEnd)
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK)->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                    });
                })
                ->orderBy('date_plan')
                ->get();
            $orders2 = Order::query()
                ->whereDate('date_plan', '>=', $this->currentDate)
                ->whereDate('date_plan', '<=', $nextTenDaysEnd)
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::BLOCK)->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                    });
                })
                ->orderBy('date_plan')
                ->get();
        } else if ($referer == 'dashboard-3') {

            $orders = Order::query()->with('positions')
                ->whereDate('date_plan', '>=', $this->currentDate)
                ->whereDate('date_plan', '<=', $nextTenDaysEnd)
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE)->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                    });
                })
                ->orderBy('date_plan')
                ->get();
            $orders2 = Order::query()
                ->whereDate('date_plan', '>=', $this->currentDate)
                ->whereDate('date_plan', '<=', $nextTenDaysEnd)
                ->whereHas('positions', function ($query) {
                    $query->whereHas('product', function ($queries) {
                        $queries->where('building_material', Product::CONCRETE)->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                    });
                })
                ->orderBy('date_plan')
                ->get();
        }

        foreach ($orders as $order) {
            $date = Carbon::parse($order->date_plan)->format('Y-m-d');

            if (!isset($dates[$date])) {
                $dates[$date] = 0;
            }
            $dates[$date] += ($order->sum);

            // foreach($order->positions as $position) {
            //     $counts[$date] += ($position->quantity);
            // }
        }

        foreach ($orders2 as $order) {
            $date = Carbon::parse($order->date_plan)->format('Y-m-d');
            $orderData[$date]++;
        }


        return response()->json([
            'entityItems' => $dates,
            'orders' => $orderData,
            'counts' => $orders,
            'labels' => $labels
        ]);
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

        $orders = Order::select('id', 'sum', 'date_plan')->withCount('positions')->with('positions')
            ->whereDate('date_plan', $date);

        $shipments = Shipment::select('id', 'created_at')->with('products')
            ->where('created_at', $date);

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

        $sum[] = $orders->whereBetween('date_plan', [$day . ' 00:00:00', $day . ' 08:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 09:00:00', $day . ' 09:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 10:00:00', $day . ' 10:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 11:00:00', $day . ' 11:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 12:00:00', $day . ' 12:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 13:00:00', $day . ' 13:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 14:00:00', $day . ' 14:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 15:00:00', $day . ' 15:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 16:00:00', $day . ' 16:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 17:00:00', $day . ' 17:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 18:00:00', $day . ' 18:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 19:00:00', $day . ' 19:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 20:00:00', $day . ' 20:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 21:00:00', $day . ' 21:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$day . ' 22:00:00', $day . ' 23:59:59'])->sum('sum');

        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 00:00:00', $day . ' 08:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 09:00:00', $day . ' 09:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 10:00:00', $day . ' 10:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 11:00:00', $day . ' 11:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 12:00:00', $day . ' 12:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 13:00:00', $day . ' 13:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 14:00:00', $day . ' 14:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 15:00:00', $day . ' 15:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 16:00:00', $day . ' 16:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 17:00:00', $day . ' 17:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 18:00:00', $day . ' 18:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 19:00:00', $day . ' 19:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 20:00:00', $day . ' 20:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 21:00:00', $day . ' 21:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$day . ' 22:00:00', $day . ' 23:59:59'])->count('positions_count');

        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 00:00:00', $day . ' 08:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 09:00:00', $day . ' 09:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 10:00:00', $day . ' 10:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 11:00:00', $day . ' 11:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 12:00:00', $day . ' 12:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 13:00:00', $day . ' 13:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 14:00:00', $day . ' 14:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 15:00:00', $day . ' 15:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 16:00:00', $day . ' 16:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 17:00:00', $day . ' 17:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 18:00:00', $day . ' 18:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 19:00:00', $day . ' 19:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 20:00:00', $day . ' 20:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 21:00:00', $day . ' 21:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$day . ' 22:00:00', $day . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });

        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 00:00:00', $day . ' 08:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 09:00:00', $day . ' 09:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 10:00:00', $day . ' 10:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 11:00:00', $day . ' 11:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 12:00:00', $day . ' 12:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 13:00:00', $day . ' 13:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 14:00:00', $day . ' 14:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 15:00:00', $day . ' 15:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 16:00:00', $day . ' 16:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 17:00:00', $day . ' 17:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 18:00:00', $day . ' 18:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 19:00:00', $day . ' 19:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 20:00:00', $day . ' 20:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 21:00:00', $day . ' 21:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$day . ' 22:00:00', $day . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });

        return response()->json([
            'sum' => $sum,
            'orders_count' => $orders_count,
            'positions_count' => $positions_count,
            'shipped_count' => $shipped_count,
            'labels' => $labels
        ]);
    }

    public function gettomorrowOrders(Request $request): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));
        $referer = explode("?", $arUrl[3])[0];
        $today = Carbon::now()->addDay()->format('d-m-Y');
        $to = Carbon::now()->addDay()->format('d-m-Y');

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

        $orders = Order::select('id', 'sum', 'date_plan')->withCount('positions')->with('positions')
            ->where('date_plan', '>=', Carbon::now()->addDay()->format('Y-m-d') . ' 00:00:00')
            ->where('date_plan', '<=', Carbon::now()->addDay()->format('Y-m-d') . ' 23:59:59');

        $shipments = Shipment::select('id', 'created_at')->with('products')
            ->where('created_at', '>=', Carbon::now()->addDay()->format('Y-m-d') . ' 00:00:00')
            ->where('created_at', '<=', Carbon::now()->addDay()->format('Y-m-d') . ' 23:59:59');

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

        $sum[] = $orders->whereBetween('date_plan', [$today . ' 00:00:00', $to . ' 08:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 09:00:00', $to . ' 09:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 10:00:00', $to . ' 10:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 11:00:00', $to . ' 11:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 12:00:00', $to . ' 12:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 13:00:00', $to . ' 13:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 14:00:00', $to . ' 14:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 15:00:00', $to . ' 15:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 16:00:00', $to . ' 16:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 17:00:00', $to . ' 17:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 18:00:00', $to . ' 18:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 19:00:00', $to . ' 19:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 20:00:00', $to . ' 20:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 21:00:00', $to . ' 21:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [$today . ' 22:00:00', $to . ' 23:59:59'])->sum('sum');

        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 00:00:00', $to . ' 08:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 09:00:00', $to . ' 09:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 10:00:00', $to . ' 10:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 11:00:00', $to . ' 11:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 12:00:00', $to . ' 12:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 13:00:00', $to . ' 13:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 14:00:00', $to . ' 14:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 15:00:00', $to . ' 15:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 16:00:00', $to . ' 16:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 17:00:00', $to . ' 17:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 18:00:00', $to . ' 18:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 19:00:00', $to . ' 19:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 20:00:00', $to . ' 20:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 21:00:00', $to . ' 21:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [$today . ' 22:00:00', $to . ' 23:59:59'])->count('positions_count');

        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 00:00:00', $to . ' 08:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 09:00:00', $to . ' 09:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 10:00:00', $to . ' 10:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 11:00:00', $to . ' 11:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 12:00:00', $to . ' 12:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 13:00:00', $to . ' 13:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 14:00:00', $to . ' 14:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 15:00:00', $to . ' 15:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 16:00:00', $to . ' 16:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 17:00:00', $to . ' 17:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 18:00:00', $to . ' 18:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 19:00:00', $to . ' 19:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 20:00:00', $to . ' 20:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 21:00:00', $to . ' 21:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [$today . ' 22:00:00', $to . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });

        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 00:00:00', $to . ' 08:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 09:00:00', $to . ' 09:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 10:00:00', $to . ' 10:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 11:00:00', $to . ' 11:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 12:00:00', $to . ' 12:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 13:00:00', $to . ' 13:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 14:00:00', $to . ' 14:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 15:00:00', $to . ' 15:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 16:00:00', $to . ' 16:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 17:00:00', $to . ' 17:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 18:00:00', $to . ' 18:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 19:00:00', $to . ' 19:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 20:00:00', $to . ' 20:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 21:00:00', $to . ' 21:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [$today . ' 22:00:00', $to . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });


        return response()->json([
            'sum' => $sum,
            'orders_count' => $orders_count,
            'positions_count' => $positions_count,
            'shipped_count' => $shipped_count,
            'labels' => $labels
        ]);
    }

    public function getthreeDaysOrders(Request $request): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));
        $referer = explode("?", $arUrl[3])[0];
        $today = Carbon::now()->format('d-m-Y');

        $sum = [];
        $positions_count = [];
        $orders_count = [];
        $shipped_count = [];
        $labels = [Carbon::now()->format('d-m'), Carbon::now()->addDay()->format('d-m'), Carbon::now()->addDays(2)->format('d-m'), Carbon::now()->addDays(3)->format('d-m')];

        $orders = Order::select('id', 'sum', 'date_plan')->withCount('positions')->with('positions')
            ->where('date_plan', '>=', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('date_plan', '<=', Carbon::now()->addDays(3)->format('Y-m-d') . ' 23:59:59')
            ->orderBy('date_plan');
        $shipments = Shipment::select('id', 'created_at')->with('products')
            ->where('created_at', '>=', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('created_at', '<=', Carbon::now()->addDays(3)->format('Y-m-d') . ' 23:59:59');

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

        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(3)->format('d-m-Y') . ' 23:59:59'])->sum('sum');

        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(3)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');

        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(3)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });

        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $product) {
                $sum += $product->quantity;
            }
            return $sum;
        });


        return response()->json([
            'sum' => $sum,
            'orders_count' => $orders_count,
            'positions_count' => $positions_count,
            'shipped_count' => $shipped_count,
            'labels' => $labels
        ]);
    }

    public function getWeek(Request $request): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));
        $referer = explode("?", $arUrl[3])[0];
        $today = Carbon::now()->format('d-m-Y');

        $sum = [];
        $positions_count = [];
        $orders_count = [];
        $shipped_count = [];
        $labels = [Carbon::now()->format('d-m'), Carbon::now()->addDay()->format('d-m'), Carbon::now()->addDays(2)->format('d-m'), Carbon::now()->addDays(3)->format('d-m'), Carbon::now()->addDays(4)->format('d-m'), Carbon::now()->addDays(5)->format('d-m'), Carbon::now()->addDays(6)->format('d-m'), Carbon::now()->addDays(7)->format('d-m')];

        $orders = Order::select('id', 'sum', 'date_plan')->withCount('positions')->with('positions')
            ->where('date_plan', '>=', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('date_plan', '<=', Carbon::now()->addDays(7)->format('Y-m-d') . ' 23:59:59')
            ->orderBy('date_plan');
        $shipments = Shipment::select('id', 'created_at')->with('products')
            ->where('created_at', '>=', Carbon::now()->format('Y-m-d') . ' 00:00:00')
            ->where('created_at', '<=', Carbon::now()->addDays(7)->format('Y-m-d') . ' 23:59:59');

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

        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(3)->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(4)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(4)->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(5)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(5)->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(6)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(6)->format('d-m-Y') . ' 23:59:59'])->sum('sum');
        $sum[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(7)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(7)->format('d-m-Y') . ' 23:59:59'])->sum('sum');

        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(3)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(4)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(4)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(5)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(5)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(6)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(6)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');
        $orders_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(7)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(7)->format('d-m-Y') . ' 23:59:59'])->count('positions_count');

        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(3)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(4)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(4)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(5)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(5)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(6)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(6)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $positions_count[] = $orders->whereBetween('date_plan', [Carbon::now()->addDays(7)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(7)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->positions as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });

        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->format('d-m-Y') . ' 00:00:00', Carbon::now()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDay()->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDay()->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(2)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(2)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(3)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(3)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(4)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(4)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(5)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(5)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(6)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(6)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });
        $shipped_count[] = $shipments->whereBetween('created_at', [Carbon::now()->addDays(7)->format('d-m-Y') . ' 00:00:00', Carbon::now()->addDays(7)->format('d-m-Y') . ' 23:59:59'])->sum(function ($items) {
            $sum = 0;
            foreach ($items->products as $position) {
                $sum += $position->quantity;
            }
            return $sum;
        });


        return response()->json([
            'sum' => $sum,
            'orders_count' => $orders_count,
            'positions_count' => $positions_count,
            'shipped_count' => $shipped_count,
            'labels' => $labels
        ]);
    }

    /**
     * @param Request $request
     * @return View
     */
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

    /**
     * @param $request
     * @return View
     */
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

        $entityItems = Order::query()->with('positions')
            ->whereDate('date_plan', $date)
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::BLOCK);
                });
            })
            ->orderBy('date_plan')
            ->get();
        $this->loadRelations($entityItems);

        $categories = Category::query()->where('building_material', Category::BLOCK)->get();
        $blocksProducts = Product::query()
            ->where('type', Product::PRODUCTS)
            ->where('building_material', Product::BLOCK)->get()->sortBy('sort');

        $materials = Product::query()
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::BLOCK)
            ->get()
            ->sortBy('sort');

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

        return view('Dashboard.block', compact(
            'urlShow',
            'entityItems',
            "resColumns",
            "entity",
            'materials',
            'blocksProducts',
            'categories',
            'dateNext',
            'datePrev',
            'date'
        ));
    }

    /**
     * @param $request
     * @return View
     */
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

        $entityItems = Order::query()->with('positions')
            ->whereDate('date_plan', $date)
            ->whereHas('positions', function ($query) {
                $query->whereHas('product', function ($queries) {
                    $queries->where('building_material', Product::CONCRETE);
                });
            })
            ->orderBy('date_plan')
            ->get();
        $this->loadRelations($entityItems);

        $materials = Product::query()
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::CONCRETE)
            ->get()
            ->sortBy('sort');

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

        return view('Dashboard.concrete', compact(
            'urlShow',
            'entityItems',
            "resColumns",
            "entity",
            'materials',
            'dateNext',
            'datePrev',
            'date'
        ));
    }

    /**
     * @param $request
     * @param $building_material
     * @return Collection|array|null
     */
    private function filterOrder($request, $building_material): Collection|array|null
    {
        $entityItems = null;

        if ($building_material !== null) {
            if ($request->filter == 'now' || !isset($request->filter)) {
                $entityItems = Order::query()
                    ->whereDate('date_plan', $this->currentDate)
                    ->whereHas('positions', function ($query) use ($building_material) {
                        $query->whereHas('product', function ($queries) use ($building_material) {
                            $queries->where(
                                'building_material',
                                ($building_material == Product::CONCRETE ? Product::CONCRETE : Product::BLOCK)
                            )
                                ->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                        });
                    })
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            } else if ($request->filter == 'tomorrow') {
                $entityItems = Order::query()
                    ->whereDate('date_plan', $this->currentDate->addDay())
                    ->whereHas('positions', function ($query) use ($building_material) {
                        $query->whereHas('product', function ($queries) use ($building_material) {
                            $queries->where(
                                'building_material',
                                ($building_material == Product::CONCRETE ? Product::CONCRETE : Product::BLOCK)
                            )
                                ->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                        });
                    })
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            } else if ($request->filter == 'three-day') {
                $entityItems = Order::query()
                    ->whereDate('date_plan', '>=', $this->currentDate)
                    ->whereDate('date_plan', '<=', $this->currentDate->addDays(3))
                    ->whereHas('positions', function ($query) use ($building_material) {
                        $query->whereHas('product', function ($queries) use ($building_material) {
                            $queries->where(
                                'building_material',
                                ($building_material == Product::CONCRETE ? Product::CONCRETE : Product::BLOCK)
                            )
                                ->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                        });
                    })
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            } else if ($request->filter == 'week') {
                $entityItems = Order::query()
                    ->whereDate('date_plan', '>=', $this->currentDate)
                    ->whereDate('date_plan', '<=', $this->currentDate->addWeek())
                    ->whereHas('positions', function ($query) use ($building_material) {
                        $query->whereHas('product', function ($queries) use ($building_material) {
                            $queries->where(
                                'building_material',
                                ($building_material == Product::CONCRETE ? Product::CONCRETE : Product::BLOCK)
                            )
                                ->orWhereIn('building_material', [Product::BLOCK, Product::CONCRETE]);
                        });
                    })
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            } else {
                $entityItems = Order::query()
                    ->whereDate('date_plan', $this->currentDate)
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            }
        } else {
            if ($request->filter == 'now' || $request->filter == 'map' || !isset($request->filter)) {
                $entityItems = Order::query()
                    ->whereDate('date_plan', $this->currentDate)
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            } else if ($request->filter == 'tomorrow') {
                $entityItems = Order::query()
                    ->whereDate('date_plan', $this->currentDate->addDay())
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            } else if ($request->filter == 'three-day') {
                $entityItems = Order::query()
                    ->whereDate('date_plan', '>=', $this->currentDate)
                    ->whereDate('date_plan', '<=', $this->currentDate->addDays(3))
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            } else if ($request->filter == 'week') {
                $entityItems = Order::query()
                    ->whereDate('date_plan', '>=', $this->currentDate)
                    ->whereDate('date_plan', '<=', $this->currentDate->addWeek())
                    ->orderBy('date_plan')
                    ->get();
                $this->loadRelations($entityItems);
            }
        }
        return $entityItems;
    }

    private function loadRelations($entityItems): void
    {
        foreach ($entityItems as $entityItem) {
            $entityItem->load('status', 'delivery', 'transport', 'contact', 'transport_type');
        }
    }

    /**
     * @return JsonResponse
     */
    public function getOrderDataForMap(): JsonResponse
    {
        $orderDates = Order::query()->whereDate('date_plan', '>=', $this->currentDate)->get();
        foreach ($orderDates as $date) {
            $date->load('delivery',);
        }
        return response()->json(['orderDates' => $orderDates]);
    }
}
