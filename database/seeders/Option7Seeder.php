<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Option7Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('options')->insert([
            [

                'name' => 'Сотрудники: Адрес, с которого будем забирать данные сотрудников',
                'code' => 'ms_employee_url',
                'value' => 'https://api.moysklad.ru/api/remap/1.2/entity/employee/',
                'module' => 'ms'

            ]
        ]);
    }
}
