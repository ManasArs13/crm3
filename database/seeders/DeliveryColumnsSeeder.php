<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeliveryColumnsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $filePath = database_path('seeders/data/delivery.csv');
        $data = [];


        if (($handle = fopen($filePath, 'r')) !== false) {
            $header = fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                $data[] = array_combine($header, $row);
            }

            fclose($handle);
        }


        foreach ($data as $row) {
            DB::table('deliveries')->updateOrInsert(
                ['id' => $row['id']],
                [
                    'locality' => $row['locality'] ?? null,
                    'region' => $row['region'] ?? null,
                    'type' => $row['type'] ?? null,
                    'population' => $row['population'] ?? null,
                    'coords' => $row['coords'] ?? null,
                    'source' => $row['source'] ?? null,
                    'sourceID' => $row['sourceID'] ?? null,
                    'route' => $row['route'] ?? null,
                ]
            );
        }
    }
}
