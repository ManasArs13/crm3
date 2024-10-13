<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryAddTimeSeeder extends Seeder
{
    public function run(): void
    {
        $sql = <<<SQL
            UPDATE deliveries d SET d.time_minute=ceil(distance*60/50)
        SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}
