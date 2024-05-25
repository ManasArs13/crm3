<?php

namespace App\Http\Controllers\Ms;

use App\Http\Controllers\Controller;
use App\Services\EntityMs\OrderMsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class CalculatorController extends Controller
{

    public function setOrderMs(Request $request, OrderMsService $orderMsService): Response
    {
        try {
            $array = $request->post();
            $result = $orderMsService->updateOrderMs($array);

            return new Response($result["name"], 200);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage()."-^-".$exception->getCode()."-^-".$exception->getLine()."-^-".$exception->getFile(), 500);
        }
    }

}
