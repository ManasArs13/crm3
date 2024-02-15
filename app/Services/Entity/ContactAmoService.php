<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\ContactAmo;
use App\Models\ContactMs;
use App\Models\Option;
use App\Models\OrderMs;
use App\Models\Shipments;
use GuzzleHttp\Client;

class ContactAmoService implements EntityInterface
{

    /**
     * @param array $datas
     * @return void
     */
    public function import(array $datas): void
    {
        foreach ($datas as $data) {
            foreach ($data as $row) {
                $entity = ContactAmo::firstOrNew(['id' => $row->getId()]);
                if ($entity->id === null) {
                    $entity->id = $row->getId();
                }
                $entity->name = $row->getName();
                $entity->is_exist = 1;
                $entity->created_at = $row->getCreatedAt();
                $customFields = $row->getCustomFieldsValues();
                $email = null;
                $phones = null;
                $phone1 = null;
                $phoneNorm = null;
                $msContactLink = null;
                $msContact = null;

                if ($customFields != null) {
                    //Получим значение поля по его ID
                    $emailField = $customFields->getBy('fieldCode', 'EMAIL');
                    if ($emailField != null) {
                        $email = (string)$emailField->getValues()[0]->getValue();
                    }

                    $msContactField = $customFields->getBy('fieldId', 604457);
                    if ($msContactField != null) {
                        $msContact = $msContactField->getValues()[0]->getValue();
                    }

                    $counterpartyField = $customFields->getBy('fieldId', 604475);
                    if ($counterpartyField != null) {
                        $msContactLink = $counterpartyField->getValues()[0]->getValue();
                    }

                    $phoneField = $customFields->getBy('fieldCode', 'PHONE');
                    if ($phoneField != null) {
                        $values = $phoneField->getValues();
                        $phones = (string)$values[0]->getValue();

                        $pattern = "/(\+7|8|7)(\s?(\-|\()?\d{3}(\-|\))?\s?\d{3}-?\d{2}-?\d{2})/";
                        $phones2 = preg_replace('/[\(,\s,\),\-, \+]/', '', $phones);
                        preg_match_all($pattern, $phones2, $matches);

                        if (isset($matches[2]))
                            $phoneNorm = "+7" . implode('', $matches[2]);

                        if (isset($values[1])) {
                            $phone1 = (string)$values[1]->getValue();
                        }
                    }
                }

                $entity->phone = $phones;
                $entity->phone_norm = $phoneNorm;
                $entity->phone1 = $phone1;

                $entity->email = $email;
                $entity->contact_ms_id = $msContact;
                $entity->contact_ms_link = $msContactLink;
                $entity->save();
            }
        }
    }

    public function update($data)
    {

        foreach ($data as $row) {
            if ($row->customFieldsValues !== null) {
                foreach ($row->customFieldsValues as $attribute) {

                    if ($attribute->fieldId == 604475) {
                        continue 2;
                    }
                }
                $contactMs = ContactMs::query()->where('contact_amo_id', $row->id);
                if ($contactMs->exists()) {

                    $budget = 0;
                    $orders = OrderMs::where('contact_ms_id', $contactMs->id)->get('id');
                    foreach ($orders as $order) {
                        $budget += Shipments::where('order_id', $order->id)->sum('suma');
                    }

                    dump($row->id, $this->actionPutRowsFromJson($row->id, $contactMs->value('id')), $budget);
                }
            }
        }
    }

    protected function  actionPutRowsFromJson($id, $msId, $budget = 0)
    {

        $accessToken = json_decode(file_get_contents(base_path('token_amocrm_widget.json')), true)['accessToken'];

        $customFieldUpdate = [
            "field_id" => 604475,
            "field_name" => "Cсылка на контрагента в моем складе",
            "field_code" => null,
            "field_type" => "url",
            "values" => [
                [
                    "value" => "https://online.moysklad.ru/#Company/edit?id=" . $msId
                ]
            ]
        ];
        $customFieldUpdate2 = [
            "field_id" => 604457,
            "field_name" => "Ид контрагента в  моем складе",
            "field_code" => null,
            "field_type" => "text",
            "values" => [
                [
                    "value" => $msId
                ]
            ]
        ];

        $customFieldUpdate3 = [
            "field_id" => 609001,
            "field_name" => "Бюджет",
            "field_code" => null,
            "field_type" => "text",
            "values" => [
                [
                    "value" => $budget
                ]
            ]
        ];


        $client = new Client([
            'base_uri' => 'https://euroblock.amocrm.ru/api/v4/',
            'headers' => [
                'Authorization' => "Bearer $accessToken",
                'Content-Type' => 'application/json',
            ],
        ]);

        try {
            $response = $client->patch("contacts/$id", [
                'json' => ['custom_fields_values' => [$customFieldUpdate, $customFieldUpdate2, $customFieldUpdate3]],
            ]);
            return $response->getStatusCode() === 200
                ? 'Custom field updated successfully.'
                : 'Error updating custom field.';
        } catch (\Exception $e) {
            return 'Request error: ' . $e->getMessage();
        }
    }
}
