<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Call;
use App\Models\EmployeeAmo;
use Illuminate\Support\Facades\Log;

class EmployeeAmoService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows as $rows) {
            foreach ($rows as $row) {

                $entity = EmployeeAmo::query()->firstOrNew(['amo_id' => $row->id]);

                if ($entity->amo_id === null) {
                    $entity->amo_id = $row->id;
                }

                $entity->name = $row->name;
                $entity->save();
            }
        }
    }
}
