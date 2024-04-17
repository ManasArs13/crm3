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
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return View
     */
    public function dashboard(Request $request): View
    {
        return $this->service->dashboard($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchOrders(Request $request): JsonResponse
    {
        return $this->service->fetchOrders($request);
    }

    public function gettodayOrders(Request $request): JsonResponse
    {
        return $this->service->gettodayOrders($request);
    }

    public function gettomorrowOrders(Request $request): JsonResponse
    {
        return $this->service->gettomorrowOrders($request);
    }

    public function getthreeDaysOrders(Request $request): JsonResponse
    {
        return $this->service->getthreeDaysOrders($request);
    }

    public function gettenDaysOrders(Request $request): JsonResponse
    {
        return $this->service->gettenDaysOrders($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrderMonth(Request $request): JsonResponse
    {
        $arUrl = explode("/", session('_previous.url'));

        if (count(explode("?", $arUrl[3])) > 1) {
            $filter = explode("?", $arUrl[3])[1];
        } else {
            return $this->service->gettodayOrders($request);
        }

        if ($filter == 'filter=tomorrow') {
            return $this->service->gettomorrowOrders($request);
        } else if ($filter == 'filter=three-day') {
            return $this->service->getthreeDaysOrders($request);
        } else if ($filter == 'filter=week') {
            return $this->service->getWeek($request);
        } else {
            return $this->service->gettodayOrders($request);
        }
    }

    public function buildingsMaterialDashboard(Request $request): View
    {
        return  $this->service->buildingsMaterialDashboard($request);
    }

    /**
     * @return JsonResponse
     */
    public function getOrderDataForMap(): JsonResponse
    {
        return  $this->service->getOrderDataForMap();
    }
}
