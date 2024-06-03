<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('times')->insert([
            [
                'time' => '08:00:00.000',
                'time_slot' => '08-09',
                'is_active'=>1
            ],
            [
                'time' => '09:00:00.000',
                'time_slot' => '09-10',
                'is_active'=>1
            ],
            [
                'time' => '10:00:00.000',
                'time_slot' => '10-11',
                'is_active'=>1
            ],
            [
                'time' => '11:00:00.000',
                'time_slot' => '11-12',
                'is_active'=>1
            ],
            [
                'time' => '12:00:00.000',
                'time_slot' => '12-13',
                'is_active'=>1
            ],
            [
                'time' => '13:00:00.000',
                'time_slot' => '13-14',
                'is_active'=>1
            ],
            [
                'time' => '14:00:00.000',
                'time_slot' => '14-15',
                'is_active'=>1
            ],
            [
                'time' => '15:00:00.000',
                'time_slot' => '15-16',
                'is_active'=>1
            ],
            [
                'time' => '16:00:00.000',
                'time_slot' => '16-17',
                'is_active'=>1
            ],
            [
                'time' => '17:00:00.000',
                'time_slot' => '17-18',
                'is_active'=>1
            ],
            [
                'time' => '18:00:00.000',
                'time_slot' => '18-19',
                'is_active'=>1
            ],
            [
                'time' => '19:00:00.000',
                'time_slot' => '19-20',
                'is_active'=>1
            ],
        ]);
    }
}
