<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Option4Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [
                'name' => 'Контрагент: Адрес метаданных контграгента в мс',
                'code' => 'ms_counterparty_meta_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/counterparty/metadata',
                'module' => 'mc'
            ],
        ]);
    }
}
