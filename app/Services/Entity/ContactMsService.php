<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\Contact;
use App\Models\ContactCategory;
use App\Models\Manager;
use App\Services\Api\MoySkladService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Arr;

class ContactMsService implements EntityInterface
{
    private Option $options;
    private MoySkladService $service;
    private string $auth;
    private $client;


    public function __construct(
        Option $options,
        MoySkladService $service
    ) {
        $login = $options::where('code', '=', 'ms_login')->first()?->value;
        $password = $options::where('code', '=', 'ms_password')->first()?->value;
        $this->options = $options;
        $this->service = $service;
        $this->auth = base64_encode($login . ':' . $password);
        $this->client = new Client();
    }

    public function import(array $rows)
    {
        $guidAttrAmoContact = $this->options::where('code', '=', "ms_counterparty_amo_id_contact_guid")->first()?->value;
        $guidAttrAmoContactLink = 'bb95261f-972b-11ed-0a80-0e9300807fe0';
        $guidAttrManager = '5e8e447f-5efa-11ef-0a80-02870015b116';

        foreach ($rows['rows'] as $row) {
            if (Arr::exists($row, 'balance')) {

                $entity = Contact::firstOrNew(['ms_id' => $row["counterparty"]["id"]]);

                if ($entity !== null) {
                    $entity->balance = $row["balance"] / 100;
                }

                $entity->save();
            } else {
                $entity = Contact::firstOrNew(['ms_id' => $row['id']]);

                if ($entity->ms_id === null) {
                    $entity->ms_id = $row['id'];
                }

                $entity->name = $row['name'];

                $phone = null;
                $phoneNorm = null;

                if (Arr::exists($row, 'phone')) {
                    $phone = $row["phone"];
                    $pattern = "/(\+7|8|7)(\s?(\-|\()?\d{3}(\-|\))?\s?\d{3}-?\d{2}-?\d{2})/";
                    $phones = preg_replace('/[\(,\s,\),\-, \+]/', '', $row["phone"]);
                    preg_match_all($pattern, $phones, $matches);
                    if (isset($matches[2]))
                        $phoneNorm = "+7" . implode('', $matches[2]);
                }

                $inn=null;
                $legalTitle=null;
                if (Arr::exists($row, 'inn'))
                    $inn = $row['inn'];

                if (Arr::exists($row, 'legalTitle'))
                    $legalTitle = $row['legalTitle'];

                $entity->inn = $inn;
                $entity->nameFull = $legalTitle;
                $entity->phone = $phone;
                $entity->phone_norm = $phoneNorm;

                $email = null;

                if (Arr::exists($row, 'email')) {
                    $email = $row["email"];
                }

                $entity->email = $email;

                $isArchived = 0;

                $description = null;
                if (Arr::exists($row, 'description')) {
                    $description = $row['description'];
                }

                $entity->description = $description;


                if (Arr::exists($row, 'archived')) {
                    $isArchived = $row["archived"];
                }

                if (Arr::exists($row, 'balance')) {
                    $entity->balance = $row["balance"] / 100;
                }

                $entity->is_archived = $isArchived;

                $amoContact = null;
                $amoContactLink = null;
                $managerId = null;

                if (isset($row["attributes"])) {
                    foreach ($row["attributes"] as $attribute) {
                        switch ($attribute["id"]) {
                            case $guidAttrAmoContact:
                                $amoContact = $attribute["value"];
                                break;
                            case $guidAttrAmoContactLink:
                                $amoContactLink = $attribute["value"];
                                break;
                            case $guidAttrManager:
                                $manager = Manager::select('id')->where('ms_id', $this->getGuidFromUrl($attribute['value']['meta']['href']))->first();
                                if ($manager) {
                                    $managerId = $manager->id;
                                }
                                break;
                        }
                    }
                }

                $entity->contact_amo_id = $amoContact;
                $entity->contact_amo_link = $amoContactLink;
                $entity->manager_id = $managerId;
                $entity->is_exist = 1;

                if (Arr::exists($row, 'created')) {
                    $entity->created_at = $row['created'];
                }

                if (Arr::exists($row, 'updated')) {
                    $entity->updated_at = $row['updated'];
                }

                if (isset($row["deleted"])) {
                    $entity->deleted_at = $row["deleted"];
                }

                $entity->save();

                if (Arr::exists($row, 'tags')) {

                    $ids = [];

                    foreach ($row['tags'] as $value) {
                        $category = ContactCategory::firstOrNew(["name" => $value]);
                        if ($category->id != null) {
                            $ids[] = $category->id;
                        } else {
                            $category->name = $value;
                            $category->save();
                            $ids[] = $category->id;
                        }
                    }
                    $entity->contact_categories()->sync($ids, ['contact_id' => $entity->id]);
                }
            }
        }
    }

    public function importOne($row)
    {
        $guidAttrAmoContact = $this->options::where('code', '=', "ms_counterparty_amo_id_contact_guid")->first()?->value;
        $guidAttrAmoContactLink = 'bb95261f-972b-11ed-0a80-0e9300807fe0';

        if (Arr::exists($row, 'balance')) {

            $entity = Contact::firstOrNew(['ms_id' => $row["counterparty"]["id"]]);

            if ($entity !== null) {
                $entity->balance = $row["balance"] / 100;
            }
        } else {
            $entity = Contact::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->name = $row['name'];

            $phone = null;
            $phoneNorm = null;

            if (Arr::exists($row, 'phone')) {
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

            if (Arr::exists($row, 'email')) {
                $email = $row["email"];
            }

            $entity->email = $email;

            $isArchived = 0;

            if (Arr::exists($row, 'archived')) {
                $isArchived = $row["archived"];
            }

            if (Arr::exists($row, 'balance')) {
                $entity->balance = $row["balance"] / 100;
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
                        case $guidAttrAmoContactLink:
                            $amoContactLink = $attribute["value"];
                            break;
                    }
                }
            }

            $entity->contact_amo_id = $amoContact;
            $entity->contact_amo_link = $amoContactLink;
            $entity->is_exist = 1;

            if (Arr::exists($row, 'created')) {
                $entity->created_at = $row['created'];
            }

            if (Arr::exists($row, 'updated')) {
                $entity->updated_at = $row['updated'];
            }

            if (isset($row["deleted"])) {
                $entity->deleted_at = $row["deleted"];
            }
        }
        $entity->save();
    }

    public function checkRows()
    {
        $url = 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/';


        $contacts = Contact::chunkById(100, function ($contacts) use ($url) {

            foreach ($contacts as $contact) {

                try {
                    usleep(200);

                    $response = $this->client->request('GET', $url . $contact->ms_id, [
                        'headers' => [
                            'Accept-Encoding' => 'gzip',
                            'Authorization' => 'Basic ' . $this->auth
                        ],
                    ]);

                    $result = json_decode($response->getBody()->getContents(), true);

                    if (isset($result["archived"])) {
                        $contact->is_archived = $result["archived"];
                        $contact->save();
                    }

                    if (isset($result["deleted"])) {
                        $contact->deleted_at = $result["deleted"];
                        $contact->save();
                    }
                } catch (RequestException  $e) {

                    if ($e->getCode() == 404) {

                        $contact->delete();

                        info($e->getMessage());
                        info('Contact №' . $contact->ms_id . ' has been deleted!');
                    }
                }
            }
        });
    }

    public function getGuidFromUrl($url): string
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }
}
