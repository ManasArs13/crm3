<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Call;
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

                    $manager_id = null;

                    switch ($row->created_by) {
                        case '11198626':
                            $manager_id = 2;
                            break;

                        case '9267290':
                            $manager_id = 1;
                            break;
                    }

                    $entity->manager_id = $manager_id;
                    $entity->save();
                }
            }
        }
    }
}
