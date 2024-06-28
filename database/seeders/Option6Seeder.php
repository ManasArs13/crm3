<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Option6Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [

                'name' => 'Перевозчик: Адрес, c которого будем забирать справочник "Перевозчик"',
                'code' => 'ms_carrier_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/customentity/d6afeece-5ad3-11ea-0a80-05280010f6ec',
                'module' => 'ms'

            ]
        ]);
    }
}
