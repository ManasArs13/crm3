<?php

namespace App\Services\Entity;

use App\Models\Option;
use App\Models\Organization;
use App\Services\Api\MoySkladService;
use Carbon\Carbon;
use App\Contracts\EntityInterface;

class OrganizationService implements EntityInterface
{
    private $service;

    public function __construct(MoySkladService $service)
    {
        $this->service = $service;
    }

    public function import(array $rows)
    {
        foreach ($rows['rows'] as $row) {

            $entity = Organization::firstOrNew(['ms_id' => $row['id']]);

            $entity->name = $row["name"];
            $entity->legal_title = $row["legalTitle"];
            $entity->legal_address = $row["legalAddress"];

            $entity->ms_id = $row['id'];
            $entity->save();
        }
    }

    public function importBalance()
    {
        $url = Option::where('code', '=', 'ms_balance_organization_url')->first()?->value;

        $balances = $this->service->actionGetRowsFromJson($url, false);

        $organisations_bd = Organization::get();

        foreach($organisations_bd as $organisation) {
            $organisation->balance = 0;
            $organisation->save();
        }

        foreach ($balances['rows'] as $row) {

            $organisation_ms_id = $this->getGuidFromUrl($row['organization']['meta']['href']);

            $entity = Organization::where('ms_id', $organisation_ms_id)->first();

            if ($entity) {
                $entity->balance += $row['balance'] / 100;
                $entity->save();
            }
        }
    }

    public function getGuidFromUrl($url)
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }
}
