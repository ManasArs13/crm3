<?php

namespace App\Services\Entity;

use App\Contracts\EntityInterface;
use App\Models\Option;
use App\Models\Product;
use App\Models\Color;
use App\Models\Category;
use App\Models\TechChartMaterial;
use App\Models\TechChartProduct;
use App\Services\Api\MoySkladService;
use Illuminate\Support\Arr;


class ProductService implements EntityInterface
{
    private $options;
    private $service;

    public function __construct(Option $options, MoySkladService $service)
    {
        $this->options = $options;
        $this->service = $service;
    }

    public function import(array $rows)
    {
        $countPalletsGuid = $this->options::where('code', '=', "ms_count_pallets_guid")->first()?->value;
        $colorGuid = $this->options::where('code', '=', "ms_product_color_guid")->first()?->value;

        foreach ($rows['rows'] as $row) {
            $entity = Product::query()->firstOrNew(['ms_id' => $row['id']]);

            if ($entity->ms_id === null) {
                $entity->ms_id = $row['id'];
                $entity->is_active = 0;
            }

            $entity->name = $row['name'];

            $categoryId = null;
            if (Arr::exists($row, 'productFolder') && isset($row["productFolder"])) {
                $category = Category::firstWhere(['ms_id' => $this->getGuidFromUrl($row["productFolder"]["meta"]["href"])]);
                $categoryId = $category->id;
            }

            $entity->category_id = $categoryId;
            if (Arr::exists($row, 'weight')) {
                $entity->weight_kg = $row["weight"];
            }

            $entity->count_pallets = 0;
            $entity->min_balance = isset($row["minimumBalance"]) ? isset($row["minimumBalance"]) : 0;

            if (isset($row["attributes"])) {
                foreach ($row["attributes"] as $attr) {

                    if ($attr["id"] == $countPalletsGuid) {
                        $entity->count_pallets = $attr["value"];
                        continue;
                    }

                    if ($attr["id"] == $colorGuid) {
                        $msId = $this->getGuidFromUrl($attr["value"]["meta"]["href"]);
                        $entityColor = Color::firstOrNew(['ms_id' => $msId]);

                        if ($entityColor->id === null) {
                            $entityColor->ms_id = $msId;
                            $entityColor->name = $attr["value"]["name"];
                            $entityColor->save();
                        }

                        $entity->color_id = $entityColor->id;
                        continue;
                    }
                }
            }

            if (isset($row["salePrices"][0])) {
                $entity->price = $row["salePrices"][0]["value"] / 100;
            } else {
                $entity->price = 0;
            }
            $entity->save();
        }
    }

    /**
     * @return void
     */
    public function importResidual(): void
    {
        $urlResidual = 'https://api.moysklad.ru/api/remap/1.2/report/stock/all/current?stockType=stock&include=zeroLines';

        $residuals = $this->service->actionGetRowsFromJson($urlResidual, false);

        foreach ($residuals as $residual) {

            $residual_material = 'не указано';
            $product = Product::where('ms_id', '=', $residual['assortmentId'])->first();

            if ($product) {
                $tech_chart_product = TechChartProduct::where('product_id', '=', $product->id)->first();

                if ($tech_chart_product) {
                    $tech_chart_materials = TechChartMaterial::where('tech_chart_id', '=', $tech_chart_product->tech_chart_id)->get();

                    if ($tech_chart_materials) {

                        if ($product->residual !== null &&  $product->residual_norm !== null) {
                            $residual_material = 'да';

                            if ($product->residual - $product->residual_norm < 0) {
                                $product_need = abs($product->residual - $product->residual_norm);

                                foreach ($tech_chart_materials as $tech_chart_material) {
                                    $material = Product::where('id', '=', $tech_chart_material->product_id)->first();
                                    $need_material = $product_need * $tech_chart_material->quantity;

                                    if ($material->residual < $need_material) {
                                        $residual_material = 'нет';
                                    }
                                }
                            }
                        }
                    }
                }

                $product->residual = $residual['stock'];
                $product->materials = $residual_material;
                $product->update();
            }
        }
    }

    public function getGuidFromUrl($url)
    {
        $arUrl = explode("/", $url);
        return $arUrl[count($arUrl) - 1];
    }
}
