<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipingPrice1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sql = <<<SQL
                        delete from `shiping_prices` where `transport_type_id`=6
                SQL;

         DB::connection()->getPdo()->exec($sql);
    }
}
