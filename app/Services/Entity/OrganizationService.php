<?php

namespace App\Services\Entity;

use App\Models\Option;
use App\Models\Organization;
use App\Services\Api\MoySkladService;
use Carbon\Carbon;
use App\Contracts\EntityInterface;

class OrganizationService implements EntityInterface
{



    public function import(array $rows)
    {

        foreach ($rows['rows'] as $row) {

            $entity = Organization::firstOrNew(['ms_id' => $row['id']]);

            $entity->name=$row["name"];
            $entity->legal_title=$row["legalTitle"];
            $entity->legal_address=$row["legalAddress"];

            $entity->ms_id=$row['id'];
            $entity->save();

        }
    }


}
