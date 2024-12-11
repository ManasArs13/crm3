<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FillCorrectsAndPositions extends Command
{
    protected $signature = 'app:fill-corrects-and-positions';
    protected $description = 'Command description';

    public function handle()
    {
        DB::transaction(function () {
            // Заполнение таблицы "corrects"
            $corrects = $this->processCorrects();

            // Заполнение таблицы "correct_positions"
            $this->fillCorrectPositions($corrects);
        });
    }

    private function processCorrects()
    {
        $corrects = [];


        $losses = DB::table('losses')->get();
        foreach ($losses as $loss) {

            $correctId = DB::table('corrects')->updateOrInsert(
                [
                    'loss_id' => $loss->id,
                ],
                [
                    'created_at' => $loss->created_at,
                    'updated_at' => $loss->updated_at,
                    'name' => $loss->name,
                    'description' => $loss->description,
                    'moment' => $loss->moment,
                    'sum' => $loss->sum,
                    'ms_id' => $loss->ms_id,
                    'deleted_at' => $loss->deleted_at,
                ]
            );


            $correctId = DB::table('corrects')->where('loss_id', $loss->id)->value('id');
            $corrects[$loss->ms_id] = $correctId;
        }


        $enters = DB::table('enters')->get();
        foreach ($enters as $enter) {

            $correctId = DB::table('corrects')->updateOrInsert(
                [
                    'enter_id' => $enter->id,
                ],
                [
                    'created_at' => $enter->created_at,
                    'updated_at' => $enter->updated_at,
                    'name' => $enter->name,
                    'description' => $enter->description,
                    'moment' => $enter->moment,
                    'sum' => $enter->sum,
                    'ms_id' => $enter->ms_id,
                    'deleted_at' => $enter->deleted_at,
                ]
            );


            $correctId = DB::table('corrects')->where('enter_id', $enter->id)->value('id');
            $corrects[$enter->ms_id] = $correctId;
        }


        $supplies = DB::table('supplies')->get();
        foreach ($supplies as $supply) {

            $correctId = DB::table('corrects')->updateOrInsert(
                [
                    'supply_id' => $supply->id,
                ],
                [
                    'created_at' => $supply->created_at,
                    'updated_at' => $supply->updated_at,
                    'name' => $supply->name,
                    'description' => $supply->description,
                    'moment' => $supply->moment,
                    'sum' => $supply->sum,
                    'ms_id' => $supply->ms_id,
                    'contact_id' => $supply->contact_id,
                    'incoming_number' => $supply->incoming_number,
                    'incoming_date' => $supply->incoming_date,
                ]
            );


            $correctId = DB::table('corrects')->where('supply_id', $supply->id)->value('id');
            $corrects[$supply->ms_id] = $correctId;
        }


        $techProcesses = DB::table('tech_processes')->get();
        foreach ($techProcesses as $process) {

            $correctId = DB::table('corrects')->updateOrInsert(
                [
                    'tech_process_id' => $process->id,
                ],
                [
                    'created_at' => $process->created_at,
                    'updated_at' => $process->updated_at,
                    'name' => $process->name,
                    'description' => $process->description,
                    'moment' => $process->moment,
                    'sum' => $process->sum,
                    'ms_id' => $process->ms_id,
                    'tech_chart_id' => $process->tech_chart_id,
                    'quantity' => $process->quantity,
                    'hours' => $process->hours,
                    'cycles' => $process->cycles,
                    'defective' => $process->defective,
                ]
            );


            $correctId = DB::table('corrects')->where('tech_process_id', $process->id)->value('id');
            $corrects[$process->ms_id] = $correctId;
        }

        return $corrects;
    }

    private function fillCorrectPositions($corrects)
    {
        $enters = DB::table('enters')->pluck('ms_id', 'id');
        $losses = DB::table('losses')->pluck('ms_id', 'id');
        $supplies = DB::table('supplies')->pluck('ms_id', 'id');
        $techProcesses = DB::table('tech_processes')->pluck('ms_id', 'id');

        $this->processEnterPositions($corrects, $enters);
        $this->processLossPositions($corrects, $losses);
        $this->processSupplyPositions($corrects, $supplies);
        $this->processTechProcessMaterials($corrects, $techProcesses);
        $this->processTechProcessProducts($corrects, $techProcesses);
    }

    private function processEnterPositions($corrects, $enters)
    {
        DB::table('enter_positions')
            ->orderBy('id')
            ->chunk(1000, function ($positions) use ($corrects, $enters) {
                $insertData = [];
                foreach ($positions as $position) {
                    $enterMsId = $enters[$position->enter_id] ?? null;
                    $correctId = $corrects[$enterMsId] ?? null;

                    if ($correctId) {

                        $existingRecord = DB::table('correct_positions')->where('enter_position_id', $position->id)
                            ->where('ms_id', $position->ms_id)
                            ->first();

                        if (!$existingRecord) {
                            $insertData[] = [
                                'product_id' => DB::table('products')->where('id', $position->product_id)->exists()
                                    ? $position->product_id
                                    : null,
                                'quantity' => $position->quantity,
                                'price' => $position->price,
                                'sum' => $position->sum,
                                'ms_id' => $position->ms_id,
                                'correct_id' => $correctId,
                                'enter_position_id' => $position->id,
                                'created_at' => $position->created_at,
                                'updated_at' => $position->updated_at,
                            ];
                        }
                    }
                }

                if (!empty($insertData)) {
                    DB::table('correct_positions')->insert($insertData);
                }
            });
    }


    private function processLossPositions($corrects, $losses)
    {
        DB::table('loss_positions')
            ->orderBy('id')
            ->chunk(1000, function ($positions) use ($corrects, $losses) {
                $insertData = [];
                foreach ($positions as $position) {
                    $lossMsId = $losses[$position->loss_id] ?? null;
                    $correctId = $corrects[$lossMsId] ?? null;

                    if ($correctId) {

                        $existingRecord = DB::table('correct_positions')->where('loss_position_id', $position->id)
                            ->where('ms_id', $position->ms_id)
                            ->first();

                        if (!$existingRecord) {
                            $insertData[] = [
                                'product_id' => DB::table('products')->where('id', $position->product_id)->exists()
                                    ? $position->product_id
                                    : null,
                                'quantity' => $position->quantity,
                                'price' => $position->price,
                                'sum' => $position->sum,
                                'ms_id' => $position->ms_id,
                                'correct_id' => $correctId,
                                'loss_position_id' => $position->id,
                                'created_at' => $position->created_at,
                                'updated_at' => $position->updated_at,
                            ];
                        }
                    }
                }

                if (!empty($insertData)) {
                    DB::table('correct_positions')->insert($insertData);
                }
            });
    }

    private function processSupplyPositions($corrects, $supplies)
    {
        DB::table('supply_positions')
            ->orderBy('id')
            ->chunk(1000, function ($positions) use ($corrects, $supplies) {
                $insertData = [];
                foreach ($positions as $position) {
                    $supplyMsId = $supplies[$position->supply_id] ?? null;
                    $correctId = $corrects[$supplyMsId] ?? null;

                    if ($correctId) {

                        $existingRecord = DB::table('correct_positions')->where('supply_position_id', $position->id)
                            ->where('ms_id', $position->ms_id)
                            ->first();

                        if (!$existingRecord) {
                            $insertData[] = [
                                'product_id' => DB::table('products')->where('id', $position->product_id)->exists()
                                    ? $position->product_id
                                    : null,
                                'quantity' => $position->quantity,
                                'price' => $position->price,
                                'ms_id' => $position->ms_id,
                                'correct_id' => $correctId,
                                'supply_position_id' => $position->id,
                                'created_at' => $position->created_at,
                                'updated_at' => $position->updated_at,
                            ];
                        }
                    }
                }

                if (!empty($insertData)) {
                    DB::table('correct_positions')->insert($insertData);
                }
            });
    }

    private function processTechProcessMaterials($corrects, $techProcesses)
    {
        DB::table('tech_process_materials')
            ->orderBy('id')
            ->chunk(1000, function ($materials) use ($corrects, $techProcesses) {
                $insertData = [];
                foreach ($materials as $material) {
                    $processMsId = $techProcesses[$material->processing_id] ?? null;
                    $correctId = $corrects[$processMsId] ?? null;

                    if ($correctId) {

                        $existingRecord = DB::table('correct_positions')->where('tech_process_material_id', $material->id)
                            ->where('ms_id', $material->ms_id)
                            ->first();

                        if (!$existingRecord) {
                            $insertData[] = [
                                'product_id' => DB::table('products')->where('id', $material->product_id)->exists()
                                    ? $material->product_id
                                    : null,
                                'quantity' => $material->quantity,
                                'quantity_norm' => $material->quantity_norm,
                                'sum' => $material->sum,
                                'ms_id' => $material->ms_id,
                                'correct_id' => $correctId,
                                'tech_process_material_id' => $material->id,
                                'created_at' => $material->created_at,
                                'updated_at' => $material->updated_at,
                            ];
                        }
                    }
                }

                if (!empty($insertData)) {
                    DB::table('correct_positions')->insert($insertData);
                }
            });
    }

    private function processTechProcessProducts($corrects, $techProcesses)
    {
        DB::table('tech_process_products')
            ->orderBy('id')
            ->chunk(1000, function ($products) use ($corrects, $techProcesses) {
                $insertData = [];
                foreach ($products as $product) {
                    $processMsId = $techProcesses[$product->processing_id] ?? null;
                    $correctId = $corrects[$processMsId] ?? null;

                    if ($correctId) {

                        $existingRecord = DB::table('correct_positions')->where('tech_process_product_id', $product->id)
                            ->where('ms_id', $product->ms_id)
                            ->first();

                        if (!$existingRecord) {
                            $insertData[] = [
                                'product_id' => DB::table('products')->where('id', $product->product_id)->exists()
                                    ? $product->product_id
                                    : null,
                                'quantity' => $product->quantity,
                                'sum' => $product->sum,
                                'ms_id' => $product->ms_id,
                                'correct_id' => $correctId,
                                'tech_process_product_id' => $product->id,
                                'created_at' => $product->created_at,
                                'updated_at' => $product->updated_at,
                            ];
                        }
                    }
                }

                if (!empty($insertData)) {
                    DB::table('correct_positions')->insert($insertData);
                }
            });
    }
}
