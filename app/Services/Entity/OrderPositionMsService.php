<?php

namespace App\Services\Entity;

use App\Models\Option;
use App\Models\OrderPositionMs;
use App\Models\Product;

class OrderPositionMsService
{
    private $options;

    public function __construct(Option $options)
    {
        $this->options = $options;
    }

    public function import($data, $order)
    {
        $count=0;
        $guids=[];
        $isDemand = true;

        $roundPallet=$this->options::where('code', '=', "round_number")->first()?->value;

        foreach ($data['rows'] as $row) {
            $entity = OrderPositionMs::firstOrNew(['id' => $row["id"]]);
            $product = Product::where('id','=',$row["assortment"]["id"])->first();

            if ($product!=null) {
                if ($entity->id === null) {
                    $entity->id = $row['id'];
                }

                if ($isDemand && ($product->min_balance_mc - $row["quantity"] + $row["shipped"] < 0)) {
                    $isDemand = false;
                }

                $entity->quantity = $row["quantity"];
                $entity->shipped = $row["shipped"];
                $entity->price = $row["price"] / 100;

                $reserve = 0;
                if (isset($row["reserve"])) {
                    $reserve = $row["reserve"];
                }

                $entity->reserve = $reserve;
                $entity->order_ms_id = $order;
                $entity->product_id = $product->id;
                $entity->weight_kg = $row["quantity"]*$product->weight_kg;

                $countsPallets=($product->count_pallets!=0)?$row["quantity"]/$product->count_pallets:0;
                $entity->count_pallets=$this->roundNumber($countsPallets, $roundPallet);
                $entity->save();

            }else{
                ++$count;
            }

            $guids[]=$entity->id;
        }
        if ($count == count($data['rows'])) {
            return ["needDelete" => 1, "isDemand" => $isDemand];
        } else {
            if (count($guids) > 0) {
                $this->deleteDeletedPositionsFromMS($order, $guids);
            }
        }

        return ["needDelete" => 0, "isDemand" => $isDemand];
    }

    public function getGuidFromUrl($url)
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }

    public function roundNumber($number, $round = 0.2)
    {
        if ($round != 0) {
            $numberNew = round($number, 2);
            $drob = $numberNew - floor($numberNew);
            if ($drob > $round) {
                return ceil($numberNew);
            }
            return floor($numberNew);
        } else {
            return $number;
        }
    }

    public function deleteDeletedPositionsFromMS($order, $guids)
    {
        OrderPositionMs::where("order_ms_id", $order)->whereNotIn('id', $guids)->delete();
    }
}
