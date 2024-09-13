<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'name' => 'Длина забора',
                'code' => 'ms_price_lists_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/pricelist/',
                'module' => 'ms'
            ]
        ]);
    }
}
