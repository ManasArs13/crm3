<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Product;
use App\Models\TechChart;
use App\Models\TechProcess;
use App\Models\TechProcessMaterial;
use App\Models\TechProcessProduct;
use App\Services\Api\MoySkladService;
use Illuminate\Database\Query\JoinClause;

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

            $entity = TechProcess::firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
            }

            if (isset($row["processingPlan"])) {
                $techchart_ms = $this->service->actionGetRowsFromJson($row['processingPlan']['meta']['href'], false);
                $techchart_bd = TechChart::select('id', 'ms_id')->where('ms_id', $techchart_ms['id'])->first();
                $entity->tech_chart_id = $techchart_bd['id'];
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

            $totalSum = 0;

            if (isset($row["products"])) {
                $products = $this->service->actionGetRowsFromJson($row['products']['meta']['href']);

                foreach ($products as $product) {
                    $entity_product = TechProcessProduct::firstOrNew(['ms_id' => $product['id']]);

                    if ($entity_product->ms_id === null) {
                        $entity_product->ms_id = $product['id'];
                    }

                    $entity_product->processing_id = $entity->id;

                    $product_ms = $this->service->actionGetRowsFromJson($product['assortment']['meta']['href'], false);
                    $product_bd = Product::select('id', 'price')->where('ms_id', $product_ms['id'])->first();

                    if ($product_bd) {
                        $sum = $product_bd->price * $product['quantity'];

                        $entity_product->product_id = $product_bd['id'];
                        $entity_product->quantity = $product['quantity'];
                        $entity_product->sum = $sum;
                        $entity_product->save();

                        $totalSum += $sum;
                    }
                }
            }

            $entity->sum = $totalSum;
            $entity->update();

            if (isset($row["materials"])) {
                $materials = $this->service->actionGetRowsFromJson($row['materials']['meta']['href']);

                foreach ($materials as $material) {
                    $entity_material = TechProcessMaterial::firstOrNew(['ms_id' => $material['id']]);


                    if ($entity_material->ms_id === null) {
                        $entity_material->ms_id = $material['id'];
                    }

                    $entity_material->processing_id = $entity->id;
                    $entity_material->quantity = $material['quantity'];

                    $product_ms = $this->service->actionGetRowsFromJson($material['assortment']['meta']['href'], false);

                    $product_bd = isset($product_ms['id']) ? Product::select('id', 'price')->where('ms_id', $product_ms['id'])->first() : null;

                    if ($product_bd) {
                        $sum = $product_bd->price * $product['quantity'];

                        $entity_material->product_id = $product_bd['id'];
                        $entity_material->sum = $sum;

                        $total_quantity_norm = 0;

                        if (count($entity->products) > 0) {
                            foreach ($entity->products as $product) {

                                $techChart = TechChart::query()
                                    ->with('materials')
                                    ->join('tech_chart_products', function (JoinClause $join) use ($product) {
                                        $join->on('tech_charts.id', '=', 'tech_chart_products.tech_chart_id')
                                            ->where('tech_chart_products.product_id', '=', $product->id);
                                    })
                                    ->First();

                                if ($techChart && isset($techChart->materials) && count($techChart->materials) > 0) {
                                    foreach ($techChart->materials as $techChartMaterial) {

                                        if ($techChartMaterial->id == $product_bd['id']) {
                                            $total_quantity_norm += $techChartMaterial->pivot->quantity * $product->pivot->quantity;
                                        }
                                    }
                                }
                            }
                        }

                        $entity_material->quantity_norm = $total_quantity_norm;
                        $entity_material->save();
                    }
                }
            }
        }
    }
}
