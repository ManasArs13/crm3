<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\Transport;
use App\Services\Api\MoySkladService;

class TransportService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Transport::firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->description = \Arr::exists($row, 'description') ? $row['description'] : "";

            $entity->save();
        }
    }

}
