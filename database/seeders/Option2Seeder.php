<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Option2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [
                'name' => 'Заказ: Url для редактирования',
                'code' => 'ms_order_edit_url',
                'value' => 'https://online.moysklad.ru/app/#customerorder/edit?id=',
                'module' => 'ms'
            ]
        ]);
    }
}
