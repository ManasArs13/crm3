<?php

namespace App\Services\Entity;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
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
        $this->currentDate =  Carbon::now()->setTime(0, 0);
        $this->columns = [
            'contact_id', 'shipped_sum', 'transport_id',
            'sum', 'delivery_id', 'weight', 'date_plan',
            'status_id', 'payed_sum',  'comment'
        ];
    }

    /**
     * @param $request
     * @return View
     */
    public function dashboard($request): View
    {
        $entityItems = $this->filterOrder($request, null);

        $materials = Product::query()->where('type', Product::MATERIAL)->get()->sortBy('sort');
        $products = Product::query()->where('type', Product::PRODUCTS)->get()->sortByDesc('sort');
        $entity = 'orders';
        $resColumns = [];

        foreach ($this->columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        uasort($resColumns, function ($a, $b) {
            return ($a > $b);
        });

        return view('Dashboard.index', compact('entityItems', "resColumns", "entity", 'products', 'materials'));
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
        $referer = explode("?", $arUrl[1])[0];
        $nextTenDaysEnd = Carbon::now()->addDays(10);
        $orders = [];
        $orders2 = [];
        $currentDates = clone $this->currentDate;
        $dates = [];
        $orderData = [];

        while ($nextTenDaysEnd >= $currentDates) {
            $dates[$currentDates->format('Y-m-d')] = 0;
            $orderData[$currentDates->format('Y-m-d')] = 0;
            $currentDates->addDay();
        }

        if ($referer == 'dashboard') {

            $orders = Order::query()
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

            $orders = Order::query()
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

            $orders = Order::query()
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
        }

        foreach ($orders2 as $order) {
            $date = Carbon::parse($order->date_plan)->format('Y-m-d');
            $orderData[$date]++;
        }

        return response()->json(['entityItems' => $dates, 'orders' => $orderData]);
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
        $entityItems = $this->filterOrder($request, Product::BLOCK);

        $categories = Category::query()->where('building_material', Category::BLOCK)->get();
        $blocksProducts = Product::query()
            ->where('type', Product::PRODUCTS)
            ->where('building_material', Product::BLOCK)->get()->sortBy('sort');
        $blocksMaterials = Product::query()
            ->where('type', Product::MATERIAL)
            ->where('building_material', Product::BLOCK)->get()->sortBy('sort');
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

        return view('Dashboard.block', compact('entityItems', "resColumns", "entity", 'blocksMaterials', 'blocksProducts', 'categories'));
    }

    /**
     * @param $request
     * @return View
     */
    private function getConcreteOrder($request): View
    {
        $entityItems = $this->filterOrder($request, Product::CONCRETE);

        $concretes = Product::query()->where('building_material', Product::CONCRETE)->get()->sortBy('sort');
        $entity = 'orders';

        $resColumns = [];
        foreach ($this->columns as $column) {
            $resColumns[$column] = trans("column." . $column);
        }

        uasort($resColumns, function ($a, $b) {
            return ($a > $b);
        });

        return view('Dashboard.concrete', compact('entityItems', "resColumns", "entity", 'concretes'));
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
