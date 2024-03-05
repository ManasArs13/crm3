<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Product;
use App\Models\Supply;
use App\Models\SupplyPosition;
use App\Models\SupplyPositions;
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
