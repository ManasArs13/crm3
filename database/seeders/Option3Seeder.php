<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Option3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [
                'name' => 'НДС',
                'code' => 'ms_vat',
                'value' => '20',
                'module' => 'ms'
            ],
            [
                'name' => 'Услуга: Доставка блоков',
                'code' => 'ms_service_delivery_block_id',
                'value' => '351221a7-f89e-11ed-0a80-01c10035feac',
                'module' => 'ms'
            ],
            [
                'name' => 'Услуга: Доставка бетона',
                'code' => 'ms_service_delivery_beton_id',
                'value' => 'e238288c-4678-11ee-0a80-03500014310c',
                'module' => 'ms'
            ],
            [
                'name' => 'Услуга: Адрес услуг в мс',
                'code' => 'ms_service_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/service/',
                'module' => 'ms'
            ]
        ]);
    }
}
