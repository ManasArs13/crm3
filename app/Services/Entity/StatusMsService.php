<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\StatusMs;

class StatusMsService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows["states"] as $row) {
            $entity = StatusMs::firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->color="#".dechex($row["color"]);

            $entity->save();
        }
    }

}
