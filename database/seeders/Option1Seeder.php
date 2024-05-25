<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Option1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [
                'name' => 'Заказ: Гуид аттрибута "Цена доставки',
                'code' => 'ms_order_delivery_price_guid',
                'value' => '7b698710-5b0d-11ea-0a80-00f200165510',
                'module' => 'ms'
            ],
            [
                'name' => 'Заказ: Гуид аттрибута "Тип доставки"',
                'code' => 'ms_order_transport_type_guid',
                'value' => '4a0518e1-a10a-11ec-0a80-087b00054a35',
                'module' => 'ms'
            ]
        ]);
    }
}
