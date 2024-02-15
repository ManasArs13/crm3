<?php

namespace App\Services\Entity;

use App\Models\ContactAmo;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use Carbon\Carbon;

class CounterpartyService
{
    private $service;
    private $options;

    public function __construct(MoySkladService  $service, Option $options)
    {
        $this->service = $service;
        $this->options = $options;
    }

    public function updateCounterpartyMs($counterparts){
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/';
        $urlAmoContact = 'https://euroblock.amocrm.ru/contacts/detail/';
        $guidAttrAmoLink = 'bb95261f-972b-11ed-0a80-0e9300807fe0';
        foreach ($counterparts as $counterparty ) {
           if (isset($counterparty["attributes"]) && isset($counterparty['phone'])) {
               foreach ($counterparty["attributes"] as $key => $attribute) {
                   $contactAmo = ContactAmo::query()->where('phone_norm' ,$counterparty['phone']);
                   if ($attribute['id'] == $guidAttrAmoLink) {
                       continue 2 ;
                   }elseif($contactAmo->exists()) {
                       foreach ($contactAmo->get() as $contact){
                           $array['name'] = $counterparty['name'];
                           $array['updated']=Carbon::now();
                           $array['phone']=$counterparty['phone'];
                           if (isset($counterparty['email'])){
                               $array['email']=$counterparty['email'];
                           }
                           $array["attributes"][] = [
                               'meta' => [
                                   'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/' . $guidAttrAmoLink,
                                   'type' => "attributemetadata",
                                   "mediaType" => "application/json"
                               ],
                               'value' => $urlAmoContact . $contact->id,
                           ];
                           $array["attributes"][] = [
                               'meta' => [
                                   'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/' . 'bb952939-972b-11ed-0a80-0e9300807fe1',
                                   'type' => "attributemetadata",
                                   "mediaType" => "application/json"
                               ],
                               'value' => (string)$contact->id,
                           ];
                           $this->service->actionPutRowsFromJson($url.$counterparty['id'], $array);
                       }

                   }
               }

           }
       }
    }


    public function issetCounterpartyMS($counterparty){
        $url = $this->options::where('code', '=', "ms_counterparty_url")->first()?->value;

        $result=$this->service->actionGetRowsFromJson($url."?filter=phone=~".substr($counterparty["phone"],2));

        if (isset($result[0])){
            return  ["isGood"=>true,"id"=> $result[0]["id"]];
        }else{
            return $this->updateCounterpartyMs($counterparty);
        }
    }

}
