<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\StatusAmo;
use Carbon\Carbon;

class StatusAmoService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows as $rows) {
            foreach ($rows as $row) {

                $entity = StatusAmo::query()->firstOrNew(['id' => $row->getId()]);
                if ($entity->id === null) {
                    $entity->id = $row->getId();
                }

                $entity->name = $row->getName();
                $entity->created_at = $entity->created_at ?? Carbon::now();
                $entity->updated_at = Carbon::now();
                $entity->save();
            }
        }
    }
}
