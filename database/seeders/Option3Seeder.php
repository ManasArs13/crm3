<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
                [
                'name' => 'Организации:Адрес с которого берем организации',
                'code' => 'ms_organization_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/organization/',
                'module' => 'ms'
            ]
        ]);
    }
}
