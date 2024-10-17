<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Manager;
use App\Models\Order;
use App\Models\OrderAmo;

class OrderAmoService implements EntityInterface
{

    public function import(array $rows)
    {
        foreach ($rows as $rows) {
            foreach ($rows as $row) {

                $entity = OrderAmo::query()->firstOrNew(['id' => $row->getId()]);

                if ($entity->id === null) {
                    $entity->id = $row->getId();
                }

                $entity->name = $row->getName();
                $entity->is_exist = 1;
                $entity->created_at = $row->getCreatedAt();
                $entity->closed_at = $row->getClosedAt();

                $msOrder = null;
                $msOrderLink = null;
                $comment = null;
                $manager = null;
                $success = false;

                if ($row->getStatusId()) {
                    $entity->status_amo_id = $row->getStatusId();
                }

                $entity->price = $row->getPrice();
                $contacts = $row->getContacts();

                if (isset($contacts[0])) {
                    $entity->contact_amo_id = $contacts[0]->getId();
                }

                if (isset($contacts[1])) {
                    $entity->contact_amo2_id = $contacts[0]->getId();
                }

                $customFields = $row->getCustomFieldsValues();

                if ($customFields != null) {

                    $msField = $customFields->getBy('fieldCode', 'AMGBP_MOYSKLAD_ORDER_ID');
                    if ($msField != null) {
                        $order = Order::where('ms_id', $msField->getValues()[0]->getValue())->select('id', 'ms_id')->First();
                        $msOrder = $order ? $order->id : null;
                    }

                    $msFieldLink = $customFields->getBy('fieldCode', 'AMGBP_MOYSKLAD_ORDER_LINK');
                    if ($msFieldLink != null) {
                        $OrderLink = $msFieldLink->getValues()[0]->getValue();
                        $msOrderLink = $OrderLink ? $OrderLink : null;
                    }

                    $commentField = $customFields->getBy('fieldId', 602581);
                    if ($commentField != null) {
                        $comment = $commentField->getValues()[0]->getValue();
                    }

                    $managerField = $customFields->getBy('fieldId', 610809);
                    if ($managerField != null) {
                        $managerName = $managerField->getValues()[0]->getValue();
                        $manager = Manager::where('name', 'LIKE', "%$managerName%")->first()?->id;
                    }

                    $successField = $customFields->getBy('fieldId', 610917);
                    if ($successField != null) {
                        $successName = $successField->getValues()[0]->getValue();
                        if ($successName == 'Да сделка') {
                            $success = true;
                        }
                    }
                }

                $entity->order_id = $msOrder;
                $entity->order_link = $msOrderLink;
                $entity->comment = $comment;
                $entity->manager_id = $manager;
                $entity->is_success = $success;
                $entity->is_exist = 1;
                $entity->save();
            }
        }
    }
}
