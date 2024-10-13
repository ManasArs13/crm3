<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DateSeeder::class,
            DeliveryAddTimeSeeder::class,
            DeliveryProductSeeder::class,
            EmployeesSeeder::class,
            ManagerSeeder::class,
            RoleSeeder::class,
            ShipingPricesSeeder::class,
            ShipingPrice1Seeder::class,
            TimeSeeder::class
        ]);
    }
}
