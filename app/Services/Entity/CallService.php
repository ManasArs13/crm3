<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Call;
use App\Models\EmployeeAmo;
use App\Models\Manager;
use App\Models\Order;
use App\Models\OrderAmo;
use Illuminate\Support\Facades\Log;

class CallService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows as $rows) {
            
            foreach ($rows as $row) {
                
                if ($row->type == 'outgoing_call' || $row->type == 'incoming_call') {

                    $entity = Call::query()->firstOrNew(['amo_id' => $row->id]);

                    if ($entity->amo_id === null) {
                        $entity->amo_id = $row->id;
                    }

                    $entity->type = $row->type;
                    $entity->created_at = $row->getCreatedAt();

                    $employee_amo_id = EmployeeAmo::query()->where('amo_id', $row->created_by)->First()?->id;

                    $entity->employee_amo_id = $employee_amo_id;

                    $entity->duration = $row->valueAfter[0]['note']['duration'];
                    $entity->save();
                }
            }
        }
    }
}
