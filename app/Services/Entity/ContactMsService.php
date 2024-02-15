<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\ContactMs;

class ContactMsService implements EntityInterface
{
    private $options;

    public function __construct(Option $options)
    {
        $this->options = $options;
    }

    public function import(array $rows)
    {
        $guidAttrAmoContact = $this->options::where('code', '=', "ms_counterparty_amo_id_contact_guid")->first()?->value;
        $guidAttrAmoContactLink = 'bb95261f-972b-11ed-0a80-0e9300807fe0';
        foreach ($rows['rows'] as $row) {
            if (\Arr::exists($row, 'balance')) {
                $entity = ContactMs::firstOrNew(['id' => $row["counterparty"]["id"]]);

                if ($entity !== null) {
                    $entity->balance = $row["balance"] / 100;
                }
            } else {
                $entity = ContactMs::firstOrNew(['id' => $row['id']]);
                if ($entity->id === null) {
                    $entity->id = $row['id'];
                }
                $entity->name = $row['name'];

                $phone = null;
                $phoneNorm = null;

                if (\Arr::exists($row, 'phone')) {
                    $phone = $row["phone"];
                    $pattern = "/(\+7|8|7)(\s?(\-|\()?\d{3}(\-|\))?\s?\d{3}-?\d{2}-?\d{2})/";
                    $phones = preg_replace('/[\(,\s,\),\-, \+]/', '', $row["phone"]);
                    preg_match_all($pattern, $phones, $matches);
                    if (isset($matches[2]))
                        $phoneNorm = "+7" . implode('', $matches[2]);
                }
                $entity->phone = $phone;
                $entity->phone_norm = $phoneNorm;

                $email = null;
                if (\Arr::exists($row, 'email')) {
                    $email = $row["email"];
                }
                $entity->email = $email;

                $isArchived = 0;
                if (\Arr::exists($row, 'archived')) {
                    $isArchived = $row["archived"];
                }
                $entity->is_archived = $isArchived;

                $amoContact = null;
                $amoContactLink = null;
                if (isset($row["attributes"])) {
                    foreach ($row["attributes"] as $attribute) {
                        switch ($attribute["id"]) {
                            case $guidAttrAmoContact:
                                $amoContact = $attribute["value"];
                                break;
                                //                            case $guidAttrIsDouble:
                                //                                $oldIsDouble=$attribute["value"];
                                //                                break;
                                //                            case $guidAttrIsPhoneChange:
                                //                                $oldIsPhoneChange=$attribute["value"];
                                //                                break;
                            case $guidAttrAmoContactLink:
                                $amoContactLink = $attribute["value"];
                                break;
                        }
                    }
                }

                $entity->contact_amo_id = $amoContact;
                $entity->contact_amo_link = $amoContactLink;
                $entity->is_exist = 1;

                if (\Arr::exists($row, 'created')) {
                    $entity->created_at = $row['created'];
                }

                if (\Arr::exists($row, 'updated')) {
                    $entity->updated_at = $row['updated'];
                }
            }
            $entity->save();
        }
    }
}
