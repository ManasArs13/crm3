<?php

namespace App\Services\Entity;

use App\Models\Option;
use App\Models\OrderPosition;
use App\Models\Product;

class OrderPositionService
{
    private $options;

    public function __construct(Option $options)
    {
        $this->options = $options;
    }

    public function import($data, $order)
    {
        $isDemand = true;
        $rows = isset($data['rows']) ? $data['rows'] : $data;

        $roundPallet = $this->options::where('code', '=', "round_number")->first()?->value;

        foreach ($rows as $row) {
            $entity = OrderPosition::firstOrNew(['ms_id' => $row["id"]]);
            $product = Product::where('ms_id','=',$row["assortment"]["id"])->first();

            if ($product) {
                if ($entity->ms_id === null) {
                    $entity->ms_id = $row['id'];
                }

                if ($product->min_balance_mc - $row["quantity"] + $row["shipped"] < 0) {
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
                $entity->order_id = $order;
                $entity->product_id = $product->id;
                $entity->weight_kg = round($row["quantity"] * $product->weight_kg, 1);

                $countsPallets = ($product->count_pallets!=0) ? $row["quantity"]/$product->count_pallets : 0;
                $entity->count_pallets = $this->roundNumber($countsPallets, $roundPallet);
                $entity->save();

            } else {
                info('Продукт ' . $row["assortment"]["id"] . ' не найден. Заказ №' . $order);
            }
        }

        return [ "isDemand" => $isDemand];
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
        OrderPosition::where("order_id", $order)->whereNotIn('id', $guids)->delete();
    }
}
