<?php

namespace App\Http\Controllers\Api\Ms;

use App\Http\Controllers\Controller;
use App\Models\Option;
use App\Services\EntityMs\OrderMsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class OrderController extends Controller
{

    public function setOrderFromCalculator(Request $request, OrderMsService $orderMsService): Response
    {
        try {
            $url=Option::where("code","ms_order_edit_url")->first()?->value;
            $array = $request->post();

            if  (!isset($array["agent"]["id"]) || $array["agent"]["id"]==null)
                throw new \Exception(trans("error.noCounterparty"));

            if ($array["agent"]["id"]=="0" && $array["agent"]["name"]=="" && $array["agent"]["phone"]=="")
                throw new \Exception(trans("error.noCounterparty"));

            if ($array["agent"]["id"]=="0"){
                unset($array["agent"]["id"]);
            }else{
                unset($array["agent"]["phone"]);
                unset($array["agent"]["name"]);
            }


            if  (!isset($array["state"]) || $array["state"]==null )
                throw new \Exception(trans("error.noState"));

            $result = $orderMsService->updateOrderMs($array);
            return new Response("<a href='".$url.$result->id."' class='font-medium text-blue-600 dark:text-blue-500 hover:underline'  target='_blank'>".$result->name."</a>", 200);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage()."-^-".$exception->getCode()."-^-".$exception->getLine()."-^-".$exception->getFile(), 500);
        }
    }

}