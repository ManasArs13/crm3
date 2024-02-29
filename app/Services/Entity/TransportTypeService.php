<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\TransportType;

class TransportTypeService implements EntityInterface
{
    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = TransportType::query()->firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
                $entity->is_manipulator = 0;
                $entity->unloading_price = 0;
                $entity->min_price = 0;
                $entity->coefficient = 0;
                $entity->min_tonnage = 0;
            }

            $entity->name = $row['name'];

            $entity->save();
        }
    }

}
