<?php

namespace App\Http\Controllers\Api\Site;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Order;
use App\Models\Product;
use App\Services\Entity\OrderService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class OrderController extends Controller
{

    public function setOrderFromCalculator(Request $request, OrderService $orderService): Response
    {
        try {
            $url = Option::where("code", "ms_orders_url")->first()?->value;
            $array = $request->post();

            if (!isset($array["agent"]["id"]) || $array["agent"]["id"] == null)
                throw new \Exception(trans("error.noCounterparty"));

            if ($array["agent"]["id"] == "0" && $array["agent"]["name"] == "" && $array["agent"]["phone"] == "")
                throw new \Exception(trans("error.noCounterparty"));

            if ($array["agent"]["id"] == "0") {
                unset($array["agent"]["id"]);
            } else {
                unset($array["agent"]["phone"]);
                unset($array["agent"]["name"]);
            }

            if (isset($array["services"])){
                $servicesNew=[];
                foreach ($array["services"] as $service){
                    if (isset($service["product_id"])){
                        $servicesNew[]=$service;
                    }
                }

                $array["services"]=$servicesNew;
            }


            if (!isset($array["state"]) || $array["state"] == null)
                throw new \Exception(trans("error.noState"));

            if (!isset($array["attributes"]["delivery"]) || $array["attributes"]["delivery"]['id'] == null)
                throw new \Exception(trans("error.noDelivery"));



            $order=$orderService->createOrder($array);



            if (isset($order["id"])) {
                return new Response("<a href='/order/" .$order["id"]. "' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'  target='_blank'>" . 'Заказ №' . $order["name"] . ' создан!' . "</a>", 200);
            } else {
                throw new \Exception(trans("error.Error"));
            }
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), 500);
            //return new Response($exception->getMessage() . "-^-" . $exception->getCode() . "-^-" . $exception->getLine() . "-^-" . $exception->getFile(), 500);
        }
    }

    public function order_get_calculator(Request $request)
    {
        $date = Carbon::parse($request['needDate']);

        $orders = Order::query()->with(
            'positions',
            'positions.product',
            'status',
            'shipment_products',
            'shipment_products.product',
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


        return response()->json($orders);
    }
}