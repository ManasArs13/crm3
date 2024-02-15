<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
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
            usleep(60000);
            $entity = TechChart::firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
            }

            if (isset($row["products"])) {
                usleep(60000);
                $products = $this->service->actionGetRowsFromJson($row['products']['meta']['href']);

                foreach ($products as $product) {
                    $entity_product = TechChartProduct::firstOrNew(['id' => $product['id']]);

                    if ($entity_product->id === null) {
                        $entity_product->id = $entity_product['id'];
                    }

                    $entity_product->tech_chart_id = $row['id'];
                    

                    $product_bd = $this->service->actionGetRowsFromJson($product['assortment']['meta']['href'], false);

                    $entity_product->product_id = $product_bd['id'];
                    $entity_product->quantity = $product['quantity'];
                    $entity_product->save();
                }
            }

            if (isset($row["materials"])) {
                usleep(60000);
                $materials = $this->service->actionGetRowsFromJson($row['materials']['meta']['href']);

                foreach ($materials as $material) {
                    $entity_material = TechChartMaterial::firstOrNew(['id' => $material['id']]);

                    if ($entity_material->id === null) {
                        $entity_material->id = $material['id'];
                    }

                    $entity_material->tech_chart_id = $row['id'];
                    $entity_material->quantity = $material['quantity'];

                    $product_bd = $this->service->actionGetRowsFromJson($material['assortment']['meta']['href'], false);
                    $entity_material->product_id = $product_bd['id'];

                    $entity_material->save();
                }
            }

            if (isset($row["description"])) {
                $entity->description = $row["description"];
            }

            if (isset($row["cost"])) {
                $entity->cost = $row["cost"] / 100;
            }

            if (isset($row["pathName"])) {
                $entity->group = $row["pathName"];
            }
            
            $entity->name = $row["name"];
            $entity->updated_at = $row["updated"];

            $entity->save();
        }
    }
}
