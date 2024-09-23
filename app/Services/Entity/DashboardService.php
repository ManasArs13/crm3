<?php

namespace App\Services\Entity;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Option;
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
            "comment",
            "delivery_id",
            "ms_link",
        ];

        $this->plant_capacity = Option::select('value')->where('code', 'plant_capacity')->first()?->value;
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
            ->with('transport', 'delivery')
            ->get()
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

        $positions_count = [];
        $shipped_count = [];
        $residual_count = [];

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

        foreach ($labels as $key => $label) {
            $positions_count[$key] = 0;
            $shipped_count[$key] = 0;
            $residual_count[$key] = 0;
        }


        foreach ($labels as $key => $label) {

            if ($key !== count($labels) - 1) {
                $nextKey = $key + 1;
                $thisTime = substr($label, 0, -1) . '1';

                $position_count = $orders->whereBetween('date_plan', [$day . ' ' . $thisTime, $day . ' ' . $labels[$nextKey]])->sum(function ($items) {
                    $sum = 0;
                    foreach ($items->positions as $position) {
                        if (
                            $position->product->building_material !== 'доставка' &&
                            $position->product->building_material !== 'не выбрано'
                        ) {
                            $sum += $position->quantity;
                        }
                    }
                    return $sum;
                });



                if ($referer == 'dashboard-3') {

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

            $shipped_count[$key] += $shipments->whereBetween('created_at', [$day . ' ' . $thisTime, $day . ' ' . $labels[$nextKey]])->sum(function ($items) {
                $sum = 0;
                foreach ($items->products as $product) {
                    if (
                        $product->product->building_material !== 'доставка' &&
                        $product->product->building_material !== 'не выбрано'
                    ) {
                        $sum += $product->quantity;
                    }
                }
                return $sum;
            });
            $residual_count[$key] += $positions_count[$key] - $shipped_count[$key];
        }

        array_shift($labels);
        array_pop($labels);

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
            ->with('transport', 'delivery')
            ->get()
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
            ->with('transport', 'delivery')
            ->get()
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

    public function processMaterials($materials){
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
}
