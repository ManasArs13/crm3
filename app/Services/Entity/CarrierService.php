<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Carrier;
use Illuminate\Support\Arr;

class CarrierService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Carrier::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->code = Arr::exists($row, 'code') ? $row['code'] : "";
            $entity->description = Arr::exists($row, 'description') ? $row['description'] : "";
            $entity->save();
        }
    }

}
