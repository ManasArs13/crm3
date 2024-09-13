<?php

namespace App\Services\Entity;

use App\Models\Option;
use App\Models\PriceListPosition;
use App\Models\Product;

class PriceListPositionService
{
    private $options;

    public function __construct(Option $options)
    {
        $this->options = $options;
    }

    public function import($data, $priceList)
    {
        $count = 0;
        $guids = [];

        $rows = $data['rows'];

        foreach ($rows as $row) {
            $entity = PriceListPosition::firstOrNew(['ms_id' => $row["id"]]);
            $product = Product::where('ms_id','=',$row["assortment"]["id"])->first();

            if ($product != null) {
                if ($entity->ms_id === null) {
                    $entity->ms_id = $row['id'];
                }

                $entity->price = isset($row["cells"][0])?$row["cells"][0]["sum"] / 100:0;

                $entity->price_list_id = $priceList;
                $entity->product_id = $product->id;

                $entity->save();
            } else {
                ++$count;
            }

            $guids[] = $entity->id;
        }
        if ($count == count($rows)) {
            return ["needDelete" => 1];
        } else {
            if (count($guids) > 0) {
                $this->deleteDeletedPositionsFromMS($priceList, $guids);
            }
        }

        return ["needDelete" => 0];
    }

    public function getGuidFromUrl($url)
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }



    public function deleteDeletedPositionsFromMS($priceList, $guids)
    {
        PriceListPosition::where("price_list_id", $priceList)->whereNotIn('id', $guids)->delete();
    }
}
