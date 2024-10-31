<?php

namespace App\Http\Controllers;

use App\Services\Entity\DashboardService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public DashboardService $service;

    public function __construct(DashboardService $service)
    {
        $this->middleware('permission:home')->only(['dashboard', 'buildingsMaterialDashboard', 'getOrderDataForMap']);
        $this->service = $service;
    }

    public function dashboard(Request $request): View
    {
        return $this->service->dashboard($request);
    }

    public function gettodayOrders(Request $request): JsonResponse
    {
        return $this->service->gettodayOrders($request, null);
    }

    public function gettenDaysOrders(Request $request): JsonResponse
    {
        return $this->service->gettenDaysOrders($request);
    }

    public function getOrders(Request $request, $date): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));

        if (count(explode("?", $arUrl[3])) > 1) {
            $date_plan = $date;
        } else {
            $date_plan = null;
        }

        return $this->service->gettodayOrders($request, $date_plan);
    }

    public function buildingsMaterialDashboard(Request $request): View
    {
        return  $this->service->buildingsMaterialDashboard($request);
    }

    public function getOrderDataForMap(): JsonResponse
    {
        return  $this->service->getOrderDataForMap();
    }
}
