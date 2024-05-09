<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Product;
use App\Models\TechChart;
use App\Models\TechChartMaterial;
use App\Models\TechChartProduct;
use App\Services\Api\MoySkladService;

class TechChartService implements EntityInterface
{
    private MoySkladService $service;

    public function __construct(MoySkladService $service)
    {
        $this->service = $service;
    }

    public function import(array $rows)
    {
        foreach ($rows["rows"] as $row) {
            $entity = TechChart::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }


            if (isset($row["description"])) {
                $entity->description = $row["description"];
            }

            if (isset($row["cost"])) {
                $entity->cost = $row["cost"] / 100;
            }

            if (isset($row["pathName"]) && $row["pathName"] !== '') {
                $entity->group = $row["pathName"];
            } else {
                $entity->group = null;
            }
            // dump($row);
            $entity->name = $row["name"];
            $entity->updated_at = $row["updated"];

            $entity->save();


            if (isset($row["products"])) {
                $products = $this->service->actionGetRowsFromJson($row['products']['meta']['href']);

                foreach ($products as $product) {
                    $entity_product = TechChartProduct::firstOrNew(['ms_id' => $product['id']]);

                    if ($entity_product->ms_id === null) {
                        $entity_product->ms_id = $entity_product['id'];
                    }

                    $entity_product->tech_chart_id = $entity->id;


                    $product_ms = $this->service->actionGetRowsFromJson($product['assortment']['meta']['href'], false);
                    $product_bd = Product::where('ms_id', $product_ms['id'])->first();

                    if ($product_bd) {
                        $entity_product->product_id = $product_bd['id'];
                        $entity_product->quantity = $product['quantity'];
                        $entity_product->save();
                    }
                }
            }

            if (isset($row["materials"])) {
                $materials = $this->service->actionGetRowsFromJson($row['materials']['meta']['href']);

                foreach ($materials as $material) {
                    $entity_material = TechChartMaterial::firstOrNew(['ms_id' => $material['id']]);

                    if ($entity_material->ms_id === null) {
                        $entity_material->ms_id = $material['id'];
                    }

                    $entity_material->tech_chart_id = $entity->id;
                    $entity_material->quantity = $material['quantity'];

                    $product_ms = $this->service->actionGetRowsFromJson($material['assortment']['meta']['href'], false);
                    $product_bd = Product::where('ms_id', $product_ms ? $product_ms['id'] : null)->first();

                    if ($product_bd) {
                        $entity_material->product_id = $product_bd['id'];
                        $entity_material->save();
                    }
                }
            }
        }
    }
}
