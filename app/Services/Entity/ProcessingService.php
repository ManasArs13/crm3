<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Processing;
use App\Models\ProcessingMaterials;
use App\Models\ProcessingProducts;
use App\Models\TechChartMaterial;
use App\Services\Api\MoySkladService;

class ProcessingService implements EntityInterface
{
    private MoySkladService $service;

    public function __construct(MoySkladService $service)
    {
        $this->service = $service;
    }

    public function import(array $rows)
    {
        foreach ($rows["rows"] as $row) {

            $entity = Processing::firstOrNew(['id' => $row['id']]);

            if ($entity->id === null) {
                $entity->id = $row['id'];
            }

            if (isset($row["processingPlan"])) {
                $techchart = $this->service->actionGetRowsFromJson($row['processingPlan']['meta']['href'], false);
                $entity->tech_chart_id = $techchart['id'];
            }

            if (isset($row["products"])) {
                $products = $this->service->actionGetRowsFromJson($row['products']['meta']['href']);

                foreach ($products as $product) {
                    $entity_product = ProcessingProducts::firstOrNew(['id' => $product['id']]);

                    if ($entity_product->id === null) {
                        $entity_product->id = $entity_product['id'];
                    }

                    $entity_product->processing_id = $row['id'];


                    $product_bd = $this->service->actionGetRowsFromJson($product['assortment']['meta']['href'], false);

                    $entity_product->product_id = $product_bd['id'];
                    $entity_product->quantity = $product['quantity'];
                    $entity_product->save();
                }
            }

            if (isset($row["materials"])) {
                $materials = $this->service->actionGetRowsFromJson($row['materials']['meta']['href']);

                foreach ($materials as $material) {
                    $entity_material = ProcessingMaterials::firstOrNew(['id' => $material['id']]);


                    if ($entity_material->id === null) {
                        $entity_material->id = $material['id'];
                    }

                    $entity_material->processing_id = $row['id'];
                    $entity_material->quantity = $material['quantity'];

                    $product_bd = $this->service->actionGetRowsFromJson($material['assortment']['meta']['href'], false);
                    $entity_material->product_id = $product_bd['id'];

                    if (isset($row["processingPlan"])) {
                        $techart_material = TechChartMaterial::where('tech_chart_id', '=', $techchart['id'])
                            ->where('product_id', '=', $product_bd['id'])
                            ->first();

                        if ($techart_material) {
                            $entity_material->quantity_norm = $row["quantity"] * $techart_material->quantity;
                        }
                    }

                    $entity_material->save();
                }
            }

            $entity->name = $row["name"];
            $entity->moment = $row["moment"];
            $entity->quantity = $row["quantity"];
            $entity->created_at = $row["created"];
            $entity->updated_at = $row["updated"];

            if (isset($row["description"])) {
                $entity->description = $row["description"];
            }

            if (isset($row["attributes"])) {
                foreach ($row["attributes"] as $attribute) {
                    switch ($attribute["id"]) {
                        case '566c3b3c-ec71-11ea-0a80-0645003c1b80':
                            $entity->hours = $attribute["value"];
                            break;
                        case 'ab38b9a1-0b26-11ec-0a80-007500104d79':
                            $entity->cycles = $attribute["value"];
                            break;
                        case 'ab38bc66-0b26-11ec-0a80-007500104d7a':
                            $entity->defective = $attribute["value"];
                            break;
                    }
                }
            }

            $entity->save();
        }
    }
}
