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
                'name' => '% от продажи блока',
                'code' => 'percent_of_the_block_sale',
                'value' => '0.003',
                'module' => 'main'
            ],
            [
                'name' => '% от продажи бетона',
                'code' => 'percent_of_the_concrete_sale',
                'value' => '0.001',
                'module' => 'main'
            ]
        ]);
    }
}
