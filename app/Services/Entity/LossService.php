<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Loss;
use App\Models\LossPosition;
use App\Models\Product;
use App\Services\Api\MoySkladService;

class LossService implements EntityInterface
{
    private MoySkladService $service;

    public function __construct(MoySkladService $service)
    {
        $this->service = $service;
    }

    public function import(array $rows)
    {
        foreach ($rows["rows"] as $row) {
            $entity = Loss::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            if (isset($row["description"])) {
                $entity->description = $row["description"];
            }
          
            if (isset($row['created'])) {
                $entity->created_at = $row['created'];
            }

            if (isset($row['updated'])) {
                $entity->updated_at = $row['updated'];
            }

            if (isset($row["moment"])) {
                $entity->moment = $row["moment"];
            }

            if (isset($row["deleted"])) {
                $entity->deleted_at = $row["deleted"];
            }

            $entity->name = $row["name"];
            $entity->sum = $row["sum"] / 100;

            $entity->save();


            if (isset($row["positions"])) {
                $products = $this->service->actionGetRowsFromJson($row['positions']['meta']['href']);

                foreach ($products as $product) {
                    $entity_product = LossPosition::firstOrNew(['ms_id' => $product['id']]);

                    if ($entity_product->ms_id === null) {
                        $entity_product->ms_id = $product['id'];
                    }

                    $entity_product->loss_id = $entity->id;

                    $product_ms = $this->service->actionGetRowsFromJson($product['assortment']['meta']['href'], false);
                    $product_bd = Product::where('ms_id', $product_ms['id'])->first();

                    if ($product_bd) {
                        $entity_product->product_id = $product_bd['id'];
                        $entity_product->quantity = $product['quantity'];
                        $entity_product->price = $product['price'] / 100;
                        $entity_product->sum = $entity_product->price * $product['quantity'];
                        $entity_product->ms_id = $product['id'];
                        $entity_product->save();
                    }
                }
            }
        }
    }
}