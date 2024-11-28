<?php

namespace App\Http\Controllers\Api\Ms;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Models\Order;
use App\Models\Product;
use App\Services\EntityMs\OrderMsService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class OrderController extends Controller
{

    public function setOrderFromCalculator(Request $request, OrderMsService $orderMsService): Response
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
                $array["attributes"]["delivery"]['id']='28803b00-5c8f-11ea-0a80-02ed000b1ce1';

            $result = $orderMsService->updateOrderMs($array);

            if (isset($result->id)) {
                return new Response("<a href='" . 'https://online.moysklad.ru/app/#customerorder/edit?id=' . $result->id . "' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'  target='_blank'>" . 'Заказ №' . $result->name . ' создан!' . "</a>", 200);
            } else {
                throw new \Exception(trans("error.Error").$result);
            }
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), 500);
            //return new Response($exception->getMessage() . "-^-" . $exception->getCode() . "-^-" . $exception->getLine() . "-^-" . $exception->getFile(), 500);
        }
    }

    public function setOrderToMs(Request $request, OrderMsService $orderMsService ): Response
    {
        $id = $request->id;

        $result = $orderMsService->createOrderToMs($id);
        $shipment = Order::find($id);

        $text=" cоздан";

        if ($shipment->ms_id!=null){
            $text=" отредактирован";
        }

        if (isset($result->id)) {
            $shipment=Order::find($id);
            $shipment->ms_id=$result->id;
            $shipment->name=$result->name;
            $shipment->save();

            return new Response(["id"=>"<a href='" . 'https://online.moysklad.ru/app/#customerorder/edit?id=' . $result->id . "' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'  target='_blank'>" . 'Заказ №' . $result->name . $text . " !</a>", "name"=>$result->name], 200);
        } else {
            return new Response(trans("error.Error").$result);
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
