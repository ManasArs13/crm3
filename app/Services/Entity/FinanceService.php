<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Contact;
use App\Models\Option;
use App\Models\Payment;
use App\Services\Api\MoySkladService;
use DateTime;
use GuzzleHttp\Client;

class FinanceService implements EntityInterface
{
    private Option $options;
    private MoySkladService $service;
    private ContactMsService $contactMsService;
    private string $auth;
    private $client;

    public function __construct(Option $options, MoySkladService $service, ContactMsService $contactMsService)
    {
        $login = $options::where('code', '=', 'ms_login')->first()?->value;
        $password = $options::where('code', '=', 'ms_password')->first()?->value;
        $this->contactMsService = $contactMsService;
        $this->options = $options;
        $this->service = $service;
        $this->auth = base64_encode($login . ':' . $password);
        $this->client = new Client();
    }

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {
            $entity = Payment::query()->firstOrNew(['ms_id' => $row["id"]]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->type = $rows['meta']['type'];

            $contact_db = null;

            if (isset($row['agent'])) {
                $agentId = $this->getGuidFromUrl($row['agent']['meta']['href']);
                $contact_db = Contact::query()->where('ms_id', $agentId)->first();

                if ($contact_db) {
                    $entity->contact_id = $contact_db->id;
                } else {
                    $row = $this->service->actionGetRowsFromJson($row['agent']['meta']['href'], false);
                    $this->contactMsService->importOne($row);

                    $contact_db = Contact::query()->where('ms_id', $agentId)->first();
                }
            }

            $entity->contact_id = $contact_db ? $contact_db->id : null;

            $entity->name = isset($row['name']) ? $row['name'] : null;
            $entity->moment = isset($row["moment"]) ? new DateTime($row["moment"]) : null;

            if (isset($row['created'])) {
                $entity->created_at = $row['created'];
            }

            if (isset($row['updated'])) {
                $entity->updated_at = $row['updated'];
            }

            $entity->description = isset($row['description']) ? $row['description'] : null;
            $entity->operation = isset($row['operations']) ? $row['operations'][0]['meta']['type'] : null;
            $entity->sum = isset($row['sum']) ? $row['sum'] : 0;

            $entity->save();
        }
    }

    public function getGuidFromUrl($url): string
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }
}
