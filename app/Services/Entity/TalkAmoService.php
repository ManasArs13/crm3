<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\TalkAmo;

class TalkAmoService implements EntityInterface
{

    /**
     * @param array $datas
     * @return void
     */
    public function import(array $datas): void
    {
        // TODO дописать импорт бесед
        foreach ($datas as $data) {
            foreach ($data as $row) {

                $entity = TalkAmo::firstOrNew(['id' => $row->getId()]);
                if ($entity->id === null) {
                    $entity->id = $row->getId();
                }

                $entity->name = $row->getName();
                $entity->created_at = $row->getCreatedAt();

                $entity->getContact();
                $entity->save();
            }
        }
    }
}
