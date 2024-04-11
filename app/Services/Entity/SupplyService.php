<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Contact;
use App\Models\Option;
use App\Models\Product;
use App\Models\Supply;
use App\Models\SupplyPosition;
use App\Services\Api\MoySkladService;

class SupplyService implements EntityInterface
{
    private MoySkladService $service;

    public function __construct(MoySkladService $service)
    {
        $this->service = $service;
    }

    public function import(array $rows)
    {
        foreach ($rows["rows"] as $row) {
            $entity = Supply::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            if (isset($row["description"])) {
                $entity->description = $row["description"];
            }

            $entity->name = $row["name"];
            $entity->moment = $row["moment"];
            $entity->created_at = $row["created"];
            $entity->updated_at = $row["updated"];
            $entity->sum = $row['sum'] / 100;

            if (isset($row["incomingNumber"])) {
                $entity->incoming_number = $row['incomingNumber'];
            }

            if (isset($row["incomingDate"])) {
                $entity->incoming_date = $row['incomingDate'];
            }

            $contact_bd = Contact::where('ms_id', $row["agent"]['meta']["href"])->first();

            if($contact_bd) {
                $entity->contact_id = $contact_bd->id;
            } else {
                $contactMs = $this->service->actionGetRowsFromJson($row["agent"]['meta']["href"], false);

                $guidAttrAmoContact = Option::where('code', '=', "ms_counterparty_amo_id_contact_guid")->first()?->value;
                $guidAttrAmoContactLink = 'bb95261f-972b-11ed-0a80-0e9300807fe0';

                $entityContact = Contact::firstOrNew(['ms_id' => $contactMs['id']]);

                    if ($entityContact->ms_id === null) {
                        $entityContact->ms_id = $contactMs['id'];
                    }

                    $entityContact->name = $contactMs['name'];

                    $phone = null;
                    $phoneNorm = null;

                    if (isset($contactMs['phone'])) {
                        $phone = $contactMs["phone"];
                        $pattern = "/(\+7|8|7)(\s?(\-|\()?\d{3}(\-|\))?\s?\d{3}-?\d{2}-?\d{2})/";
                        $phones = preg_replace('/[\(,\s,\),\-, \+]/', '', $contactMs["phone"]);
                        preg_match_all($pattern, $phones, $matches);
                        if (isset($matches[2]))
                            $phoneNorm = "+7" . implode('', $matches[2]);
                    }

                    $entityContact->phone = $phone;
                    $entityContact->phone_norm = $phoneNorm;

                    $email = null;

                    if (isset($contactMs['email'])) {
                        $email = $contactMs["email"];
                    }

                    $entityContact->email = $email;

                    $isArchived = 0;

                    if (isset($contactMs['archived'])) {
                        $isArchived = $contactMs["archived"];
                    }

                    $entityContact->is_archived = $isArchived;

                    $amoContact = null;
                    $amoContactLink = null;

                    if (isset($contactMs["attributes"])) {
                        foreach ($contactMs["attributes"] as $attribute) {
                            switch ($attribute["id"]) {
                                case $guidAttrAmoContact:
                                    $amoContact = $attribute["value"];
                                    break;
                                case $guidAttrAmoContactLink:
                                    $amoContactLink = $attribute["value"];
                                    break;
                            }
                        }
                    }

                    $entityContact->contact_amo_id = $amoContact;
                    $entityContact->contact_amo_link = $amoContactLink;
                    $entityContact->is_exist = 1;

                    if ($contactMs['created']) {
                        $entityContact->created_at = $contactMs['created'];
                    }

                    if ($contactMs['updated']) {
                        $entityContact->updated_at = $contactMs['updated'];
                    }

                    $entityContact->save();

                    $entity->contact_id = $entityContact->id;
            }


            $entity->save();

            if (isset($row["positions"])) {

                $positions = $this->service->actionGetRowsFromJson($row['positions']['meta']['href']);

                foreach ($positions as $position) {
                    $entity_position = SupplyPosition::firstOrNew(['ms_id' => $position['id']]);

                    if ($entity_position->ms_id === null) {
                        $entity_position->ms_id = $position['id'];
                    }

                    $entity_position->supply_id = $entity->id;
                    $entity_position->quantity = $position['quantity'];
                    $entity_position->price = $position['price'] / 100;

                    $product_bd = Product::where('ms_id', $this->getGuidFromUrl($position['assortment']['meta']['href']))->first();
                    
                    if($product_bd) {
                        $entity_position->product_id = $product_bd['id'];
                        $entity_position->save();
                    }                                     
                }
            }

        }
    }

    public function getGuidFromUrl($url)
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }
}
