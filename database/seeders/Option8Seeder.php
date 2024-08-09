<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Option8Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [
                'name' => 'Менедженры: Адрес, с которого будем забирать данные менеджеров',
                'code' => 'ms_manager_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customentity/9ff3e917-c885-11ed-0a80-07580004cc0a',
                'module' => 'ms'
            ]
        ]);
    }
}
