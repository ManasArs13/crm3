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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrderMonth(Request $request): JsonResponse
    {
        return $this->service->getOrderMonth( $request);
    }

    public function buildingsMaterialDashboard(Request $request):View
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
