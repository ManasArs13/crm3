<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\PriceList;
use App\Services\Api\MoySkladService;
use App\Models\Option;

use Carbon\Carbon;
use DateTime;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


class PriceListService implements EntityInterface
{
    private Option $options;
    private PriceListPositionService $priceListPositionService;
    private MoySkladService $service;


    public function __construct(Option $options, PriceListPositionService $priceListPositionService, MoySkladService $service)
    {
        $this->options = $options;
        $this->priceListPositionService = $priceListPositionService;
        $this->service = $service;
    }

    /**
     * @throws Exception
     */
    public function import(array $rows): void
    {
        foreach ($rows['rows'] as $row) {
            $entity = PriceList::query()->firstOrNew(['ms_id' => $row["id"]]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            $entity->created_at = isset($row["moment"]) ? new DateTime($row["moment"]) : new DateTime();;
            $entity->name=$row["name"];
            $entity->description=isset($row["description"])?$row["description"]:"";

            if (Arr::exists($row, 'updated')) {
                $entity->updated_at = $row['updated'];
            }

            $entity->save();

            $needDelete = $this->priceListPositionService->import($row["positions"], $entity->id);

            if ($needDelete["needDelete"]) {
                $entity->positions()->delete();
                $entity->delete();
            }
        }
    }
}
