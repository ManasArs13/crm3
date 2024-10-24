<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Contact;
use App\Models\ContactAmo;
use App\Models\EmployeeAmo;
use App\Models\TalkAmo;
use App\Services\Api\AmoService;
use Illuminate\Support\Facades\Log;

class TalkAmoService implements EntityInterface
{
    public function import(array $datas): void
    {
        foreach ($datas[0] as $data) {

            foreach ($data as $row) {

                $entity = TalkAmo::query()->firstOrNew(['amo_id' => $row->entityId]);

                if ($entity->amo_id === null) {
                    $entity->amo_id = $row->entityId;
                }

                $entity->created_at = $row->created_at;

                $employee_amo_id = EmployeeAmo::query()->where('amo_id', $row->created_by)->First()?->id;
                $entity->employee_amo_id = $employee_amo_id;

                $entity->save();
            }
        }
    }

    public function importOne($data): void
    {
        $contact_bd = ContactAmo::query()->where(['id' => $data->contactId])->first();
        $entity = TalkAmo::query()->where(['amo_id' => $data->talkId])->first();

        if ($contact_bd && $entity) {
            $entity->contact_amo_id = $contact_bd->id;
            $entity->phone = $contact_bd->phone_norm;

            $entity->update();
        }
    }
}
