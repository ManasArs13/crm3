<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Доставка продукции',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'доставка'
            ],
            [
                'name' => 'Доставка продукции товар до 2023 года',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'доставка'
            ],
            [
                'name' => 'Простой',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'доставка'
            ],
            [
                'name' => 'Простой не использовать',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'доставка'
            ],
            [
                'name' => 'Услуга доставки автобетонанасоса',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'доставка'
            ],
            [
                'name' => 'Услуга доставки бетона',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'доставка'
            ],
            [
                'name' => 'Услуга работы ленты',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'доставка'
            ],
        ]);
    }
}
