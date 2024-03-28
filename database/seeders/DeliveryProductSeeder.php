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
                'building_material' => 'не выбрано'
            ],
            [
                'name' => 'Доставка продукции товар до 2023 года',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'не выбрано'
            ],
            [
                'name' => 'Простой',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'не выбрано'
            ],
            [
                'name' => 'Простой не использовать',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'не выбрано'
            ],
            [
                'name' => 'Услуга доставки автобетонанасоса',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'не выбрано'
            ],
            [
                'name' => 'Услуга доставки бетона',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'не выбрано'
            ],
            [
                'name' => 'Услуга работы ленты',
                'category_id' => 20,
                'type' => 'продукция',
                'building_material' => 'не выбрано'
            ],
        ]);
    }
}
