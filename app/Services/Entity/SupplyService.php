<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Supply;
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
            $entity = Supply::firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
                $entity->is_active = 0;
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

            if (isset($row["positions"])) {
                usleep(70000);
                $positions = $this->service->actionGetRowsFromJson($row['positions']['meta']['href']);

                foreach ($positions as $position) {
                    $entity_position = SupplyPositions::firstOrNew(['id' => $position['id']]);

                    if ($entity_position->id === null) {
                        $entity_position->id = $position['id'];
                    }

                    $entity_position->supply_id = $row['id'];
                    $entity_position->quantity = $position['quantity'];
                    $entity_position->price = $position['price'] / 100;

                    usleep(70000);
                    $product_bd = $this->service->actionGetRowsFromJson($position['assortment']['meta']['href'], false);
                    $entity_position->product_id = $product_bd['id'];


                    $entity_position->save();
                }
            }

            if (isset($row["agent"])) {
                usleep(70000);
                $agent = $this->service->actionGetRowsFromJson($row['agent']['meta']['href'], false);
                $entity->contact_ms_id = $agent['id'];
            }

            $entity->save();
        }
    }
}
