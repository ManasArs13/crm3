<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Services\Api\MoySkladService;
use App\Models\Manager;

class ManagerService implements EntityInterface
{
    private Option $options;

    public MoySkladService $service;

    public function __construct(Option $options, MoySkladService $service)
    {
        $this->service = $service;
        $this->options = $options;
    }

    /**
     * @param array $rows
     * @return void
     */
    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {

            $entity = Manager::query()->firstOrNew(['ms_id' => $row["id"]]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->name = $row['name'];
            $entity->save();
        }
    }
}
