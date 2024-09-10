<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('managers')->insert(
            [
                array(
                  'id' => 1,
                  'created_at' => '2024-08-09 16:47:51',
                  'updated_at' => '2024-08-09 16:47:51',
                  'name' => 'Ярослав',
                  'ms_id' => '2fbde309-4339-11ef-0a80-037400053c2b'
                ),
                array(
                  'id' => 2,
                  'created_at' => '2024-08-09 16:47:51',
                  'updated_at' => '2024-08-09 16:47:51',
                  'name' => 'Екатерина',
                  'ms_id' => '36f300d2-4339-11ef-0a80-1582000551bb'
                ),
                array(
                  'id' => 3,
                  'created_at' => '2024-08-09 16:47:51',
                  'updated_at' => '2024-08-09 16:47:51',
                  'name' => 'Общая Еврогрупп',
                  'ms_id' => '3ba2e5b7-4339-11ef-0a80-01930005e695'
                ),
                array(
                  'id' => 4,
                  'created_at' => '2024-08-15 18:30:00',
                  'updated_at' => '2024-08-15 18:30:00',
                  'name' => 'Пока не ясно',
                  'ms_id' => 'f0c8dd55-594b-11ef-0a80-08a30007ff62'
                ) 
            ]);
    }
}
