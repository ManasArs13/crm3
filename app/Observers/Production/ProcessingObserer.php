<?php

namespace App\Observers\Production;

use App\Models\TechChart;
use App\Models\TechProcess;
use App\Models\TechProcessMaterial;
use App\Models\TechProcessProduct;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\Log;

class ProcessingObserer
{
    public function created(TechProcess $techProcess): void
    {
        $products = TechProcessProduct::where('processing_id', $techProcess->id)->get();
        $materials = TechProcessMaterial::where('processing_id', $techProcess->id)->get();

        if (count($materials) > 0) {

            foreach ($materials as $material) {

                $quantity_norm = 0;

                if (count($products) > 0) {
                    foreach ($products as $product) {

                        $techChart = TechChart::query()
                            ->with('materials')
                            ->join('tech_chart_products', function (JoinClause $join) use ($product) {
                                $join->on('tech_charts.id', '=', 'tech_chart_products.tech_chart_id')
                                    ->where('tech_chart_products.product_id', '=', $product->product_id);
                            })
                            ->First();

                        if ($techChart && isset($techChart->materials) && count($techChart->materials) > 0) {
                            foreach ($techChart->materials as $techChartMaterials) {
                                 
                                if ($techChartMaterials->id == $material->product_id) {
                                    $quantity_norm += $techChartMaterials->pivot->quantity * $techProcess->quantity;
                                }
                            }
                        }
                    }
                }

                $material->quantity_norm = $quantity_norm;
                $material->save();
            }
        }
    }

    public function updated(TechProcess $techProcess): void
    {
        $products = TechProcessProduct::where('processing_id', $techProcess->id)->get();
        $materials = TechProcessMaterial::where('processing_id', $techProcess->id)->get();

        if (count($materials) > 0) {

            foreach ($materials as $material) {

                $quantity_norm = 0;

                if (count($products) > 0) {
                    foreach ($products as $product) {

                        $techChart = TechChart::query()
                            ->with('materials')
                            ->join('tech_chart_products', function (JoinClause $join) use ($product) {
                                $join->on('tech_charts.id', '=', 'tech_chart_products.tech_chart_id')
                                    ->where('tech_chart_products.product_id', '=', $product->product_id);
                            })
                            ->First();

                        if ($techChart && isset($techChart->materials) && count($techChart->materials) > 0) {
                            foreach ($techChart->materials as $techChartMaterials) {
                                 
                                if ($techChartMaterials->id == $material->product_id) {
                                    $quantity_norm += $techChartMaterials->pivot->quantity * $techProcess->quantity;
                                }
                            }
                        }
                    }
                }

                $material->quantity_norm = $quantity_norm;
                $material->save();
            }
        }
    }

    /**
     * Handle the TechProcess "deleted" event.
     */
    public function deleted(TechProcess $techProcess): void
    {
        //
    }

    /**
     * Handle the TechProcess "restored" event.
     */
    public function restored(TechProcess $techProcess): void
    {
        //
    }

    /**
     * Handle the TechProcess "force deleted" event.
     */
    public function forceDeleted(TechProcess $techProcess): void
    {
        //
    }
}
