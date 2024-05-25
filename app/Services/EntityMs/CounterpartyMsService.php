<?php

namespace App\Services\EntityMs;

use App\Models\Option;
use App\Services\Api\MoySkladService;

class CounterpartyMsService
{

    private $moySkladService;

    public function __construct(MoySkladService $moySkladService){
        $this->moySkladService=$moySkladService;
    }

    function updateCounterpartyMs($msCounterparty)
    {
        $urlCounterparty = Option::where('code', '=',"ms_counterparty_url")->first()?->value;


        if (!isset($msCounterparty["id"]) && isset($msCounterparty["phone"])){
            $msCounterparty["id"]=null;
            $counterparty=$this->moySkladService->actionGetRowsFromJson($urlCounterparty."?filter=phone=".urlencode($msCounterparty["phone"]));
            if (isset($counterparty[0])){
                $msCounterparty["id"]=\Arr::exists($counterparty[0],"id")?$counterparty[0]["id"]:null;
            }
        }


        if (!isset($msCounterparty["id"]) || $msCounterparty["id"]==null) {
            return $this->moySkladService->actionPostRowsFromJson($urlCounterparty, $msCounterparty);
        }else{
            return $this->moySkladService->actionPutRowsFromJson($urlCounterparty.$msCounterparty["id"], $msCounterparty);
        }
    }
}
