<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Delivery;

class DeliveryService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Delivery::firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->distance = \Arr::exists($row, 'code') ? intval($row['code']) : 0;

            $entity->save();
        }
    }

}
