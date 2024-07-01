<?php

namespace App\Console\Commands;

use App\Models\Contact;
use App\Models\ContactAmo;
use App\Models\ContactAmoContact;
use App\Models\Option;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use GuzzleHttp\Exception\RequestException;

class SyncContactMsAmo extends Command
{
    protected $signature = 'app:sync-contact-ms-amo';
    protected $description = 'Command description';

    public function handle(): void
    {
        $accessToken = json_decode(file_get_contents(base_path('token_amocrm_widget.json')), true)['accessToken'];

        $login = Option::where('code', '=', 'ms_login')->first()?->value;
        $password = Option::where('code', '=', 'ms_password')->first()?->value;
        $authMC = base64_encode($login . ':' . $password);

        $contacts_ms = Contact::query()
            ->groupBy('phone_norm')
            ->havingRaw('COUNT(*) = 1')
            //->whereNull('contact_amo_link')
            ->where('updated_at', '>', Carbon::now()->subDays(20))
            ->get();

        foreach ($contacts_ms as $contact) {
            $contactAmo = ContactAmo::query()->where('phone_norm', $contact->phone_norm)->first();

            if ($contactAmo) {
                $contactMsContactAmo = ContactAmoContact::firstOrNew([
                    'contact_id'  =>  $contact->id,
                    'contact_amo_id' =>  $contactAmo->id,
                ]);

                if ($contactAmo->is_exist == 1) {
                    $contact->contact_amo_link = 'https://euroblock.amocrm.ru/contacts/detail/' . $contactAmo->id;
                    $contact->contact_amo_id = $contactAmo->id;
                    $contact->update();
                    //Отправка данных в МС
                    $counterparty = [];

                    //$counterparty['name'] = $contact->name;
                    $counterparty['updated'] = Carbon::now();
                    //$counterparty['phone'] = $contact->phone;

                    if (isset($contact->email)) {
                        $counterparty['email'] = $contact->email;
                    }

                    $counterparty["attributes"][] = [
                        'meta' => [
                            'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/bb95261f-972b-11ed-0a80-0e9300807fe0',
                            'type' => "attributemetadata",
                            "mediaType" => "application/json"
                        ],
                        'value' => 'https://euroblock.amocrm.ru/contacts/detail/' . $contactAmo->id,
                    ];

                    $counterparty["attributes"][] = [
                        'meta' => [
                            'href' => 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata/attributes/bb952939-972b-11ed-0a80-0e9300807fe1',
                            'type' => "attributemetadata",
                            "mediaType" => "application/json"
                        ],
                        'value' => (string)$contactAmo->id,
                    ];

                    try {
                        $clientMC = new Client();
                        $responseMC = $clientMC->request("PUT", 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/' . $contact->ms_id, [
                            'headers' => [
                                'Accept-Encoding' => 'gzip',
                                'Authorization' => 'Basic ' . $authMC
                            ],
                            'json' => $counterparty
                        ]);
                    } catch (RequestException  $e) {
                        if ($responseMC->getStatusCode() == 400) {
                            $contact->is_exist = 0;
                            $contact->update();
                        } else {
                            info($e->getMessage());
                        }
                    }
                } else {
                    $contact->contact_amo_link = null;
                    $contact->contact_amo_id = $contactAmo->id;
                    $contact->update();
                }

                $contactAmo->contact_ms_link = 'https://online.moysklad.ru/#Company/edit?id=' . $contact->ms_id;
                $contactAmo->contact_ms_id = $contact->ms_id;
                $contactAmo->update();

                // Отправка данных в АМО
                $customFieldUpdate = [
                    "field_id" => 604475,
                    "field_name" => "Cсылка на контрагента в моем складе",
                    "field_code" => null,
                    "field_type" => "url",
                    "values" => [
                        [
                            "value" => $contactAmo->contact_ms_link
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
                            "value" => $contactAmo->contact_ms_id
                        ]
                    ]
                ];

                $clientAMO = new Client([
                    'base_uri' => 'https://euroblock.amocrm.ru/api/v4/',
                    'headers' => [
                        'Authorization' => "Bearer $accessToken",
                        'Content-Type' => 'application/json',
                    ],
                ]);

                try {
                    $responseAMO = $clientAMO->patch("contacts/$contactAmo->id", [
                        'json' => ['custom_fields_values' => [$customFieldUpdate, $customFieldUpdate2]],
                    ]);
                } catch (RequestException  $e) {
                    if ($responseAMO->getStatusCode() == 400) {
                        $contact->contact_amo_link = null;
                        $contact->update();

                        $contactAmo->is_exist = 0;
                        $contactAmo->update();
                    } else {
                        info($e->getMessage());
                    }
                }
                $contactMsContactAmo->contact_id = $contact->id;
                $contactMsContactAmo->contact_amo_id = $contactAmo->id;
                $contactMsContactAmo->ms_id = $contact->ms_id;

                $contactMsContactAmo->save();
            }
        }
    }
}
