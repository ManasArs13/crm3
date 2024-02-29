<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Transport;
use Illuminate\Support\Arr;

class TransportService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Transport::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->description = Arr::exists($row, 'description') ? $row['description'] : "";

            $entity->save();
        }
    }

}
