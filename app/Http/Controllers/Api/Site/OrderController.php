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
                return new Response("<a href='/order/" .$order["id"]. "' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'  target='_blank'>" . 'Заказ №' . $order["id"] . ' создан!' . "</a>", 200);
            } else {
                throw new \Exception(trans("error.Error"));
            }
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), 500);
            //return new Response($exception->getMessage() . "-^-" . $exception->getCode() . "-^-" . $exception->getLine() . "-^-" . $exception->getFile(), 500);
        }
    }


}
