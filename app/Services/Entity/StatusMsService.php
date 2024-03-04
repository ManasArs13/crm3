<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Status;

class StatusMsService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows["states"] as $row) {
            $entity = Status::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->color="#".dechex($row["color"]);

            $entity->save();
        }
    }

}
