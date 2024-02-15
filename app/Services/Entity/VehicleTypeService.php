<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\VehicleType;
use App\Services\Api\MoySkladService;

class VehicleTypeService implements EntityInterface
{
    private $service;
    private $options;

    public function __construct(MoySkladService $service, Option $options)
    {
        $this->service = $service;
        $this->options = $options;
    }

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = VehicleType::query()->firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
                $entity->is_manipulator=0;
                $entity->unloading_price=0;
                $entity->min_price=0;
                $entity->coefficient=0;
                $entity->min_tonnage=0;
            }

            $entity->name = $row['name'];

            $entity->save();
        }
    }

}
