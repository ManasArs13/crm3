<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ShipingPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shiping_prices')->insert([
                array(
                  'id' => 1,
                  'distance' => 25,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 2,
                  'distance' => 30,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 3,
                  'distance' => 40,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 4,
                  'distance' => 50,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 5,
                  'distance' => 60,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 6,
                  'distance' => 70,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 7,
                  'distance' => 80,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 8,
                  'distance' => 90,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 9,
                  'distance' => 100,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 10,
                  'distance' => 120,
                  'tonnage' => 1.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 11,
                  'distance' => 140,
                  'tonnage' => 1.0,
                  'price' => 2100,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 12,
                  'distance' => 160,
                  'tonnage' => 1.0,
                  'price' => 2300,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 13,
                  'distance' => 180,
                  'tonnage' => 1.0,
                  'price' => 2400,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 14,
                  'distance' => 200,
                  'tonnage' => 1.0,
                  'price' => 2500,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 15,
                  'distance' => 220,
                  'tonnage' => 1.0,
                  'price' => 2600,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 16,
                  'distance' => 25,
                  'tonnage' => 2.0,
                  'price' => 1000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 17,
                  'distance' => 30,
                  'tonnage' => 2.0,
                  'price' => 1000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 18,
                  'distance' => 40,
                  'tonnage' => 2.0,
                  'price' => 1100,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 19,
                  'distance' => 50,
                  'tonnage' => 2.0,
                  'price' => 1200,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 20,
                  'distance' => 60,
                  'tonnage' => 2.0,
                  'price' => 1300,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 21,
                  'distance' => 70,
                  'tonnage' => 2.0,
                  'price' => 1500,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 22,
                  'distance' => 80,
                  'tonnage' => 2.0,
                  'price' => 1600,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 23,
                  'distance' => 90,
                  'tonnage' => 2.0,
                  'price' => 1700,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 24,
                  'distance' => 100,
                  'tonnage' => 2.0,
                  'price' => 1900,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 25,
                  'distance' => 120,
                  'tonnage' => 2.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 26,
                  'distance' => 140,
                  'tonnage' => 2.0,
                  'price' => 2100,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 27,
                  'distance' => 160,
                  'tonnage' => 2.0,
                  'price' => 2300,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 28,
                  'distance' => 180,
                  'tonnage' => 2.0,
                  'price' => 2400,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 29,
                  'distance' => 200,
                  'tonnage' => 2.0,
                  'price' => 2500,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 30,
                  'distance' => 220,
                  'tonnage' => 2.0,
                  'price' => 2600,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 31,
                  'distance' => 25,
                  'tonnage' => 3.0,
                  'price' => 1000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 32,
                  'distance' => 30,
                  'tonnage' => 3.0,
                  'price' => 1000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 33,
                  'distance' => 40,
                  'tonnage' => 3.0,
                  'price' => 1100,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 34,
                  'distance' => 50,
                  'tonnage' => 3.0,
                  'price' => 1200,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 35,
                  'distance' => 60,
                  'tonnage' => 3.0,
                  'price' => 1300,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 36,
                  'distance' => 70,
                  'tonnage' => 3.0,
                  'price' => 1500,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 37,
                  'distance' => 80,
                  'tonnage' => 3.0,
                  'price' => 1600,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 38,
                  'distance' => 90,
                  'tonnage' => 3.0,
                  'price' => 1700,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 39,
                  'distance' => 100,
                  'tonnage' => 3.0,
                  'price' => 1900,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 40,
                  'distance' => 120,
                  'tonnage' => 3.0,
                  'price' => 2000,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 41,
                  'distance' => 140,
                  'tonnage' => 3.0,
                  'price' => 2100,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 42,
                  'distance' => 160,
                  'tonnage' => 3.0,
                  'price' => 2300,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 43,
                  'distance' => 180,
                  'tonnage' => 3.0,
                  'price' => 2400,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 44,
                  'distance' => 200,
                  'tonnage' => 3.0,
                  'price' => 2500,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 45,
                  'distance' => 220,
                  'tonnage' => 3.0,
                  'price' => 2600,
                  'transport_type_id' => 6
                ),
                array(
                  'id' => 46,
                  'distance' => 25,
                  'tonnage' => 3.0,
                  'price' => 900,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 47,
                  'distance' => 30,
                  'tonnage' => 3.0,
                  'price' => 1067,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 48,
                  'distance' => 40,
                  'tonnage' => 3.0,
                  'price' => 1200,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 49,
                  'distance' => 50,
                  'tonnage' => 3.0,
                  'price' => 1367,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 50,
                  'distance' => 60,
                  'tonnage' => 3.0,
                  'price' => 1500,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 51,
                  'distance' => 70,
                  'tonnage' => 3.0,
                  'price' => 1667,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 52,
                  'distance' => 80,
                  'tonnage' => 3.0,
                  'price' => 1800,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 53,
                  'distance' => 90,
                  'tonnage' => 3.0,
                  'price' => 1967,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 54,
                  'distance' => 100,
                  'tonnage' => 3.0,
                  'price' => 2100,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 55,
                  'distance' => 120,
                  'tonnage' => 3.0,
                  'price' => 2267,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 56,
                  'distance' => 140,
                  'tonnage' => 3.0,
                  'price' => 2400,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 57,
                  'distance' => 160,
                  'tonnage' => 3.0,
                  'price' => 2567,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 58,
                  'distance' => 180,
                  'tonnage' => 3.0,
                  'price' => 2700,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 59,
                  'distance' => 200,
                  'tonnage' => 3.0,
                  'price' => 2867,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 60,
                  'distance' => 220,
                  'tonnage' => 3.0,
                  'price' => 3000,
                  'transport_type_id' => 5
                ),
                array(
                  'id' => 61,
                  'distance' => 25,
                  'tonnage' => 6.0,
                  'price' => 678,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 62,
                  'distance' => 30,
                  'tonnage' => 6.0,
                  'price' => 791,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 63,
                  'distance' => 40,
                  'tonnage' => 6.0,
                  'price' => 904,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 64,
                  'distance' => 50,
                  'tonnage' => 6.0,
                  'price' => 1017,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 65,
                  'distance' => 60,
                  'tonnage' => 6.0,
                  'price' => 1130,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 66,
                  'distance' => 70,
                  'tonnage' => 6.0,
                  'price' => 1243,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 67,
                  'distance' => 80,
                  'tonnage' => 6.0,
                  'price' => 1356,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 68,
                  'distance' => 90,
                  'tonnage' => 6.0,
                  'price' => 1469,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 69,
                  'distance' => 100,
                  'tonnage' => 6.0,
                  'price' => 1582,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 70,
                  'distance' => 120,
                  'tonnage' => 6.0,
                  'price' => 1695,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 71,
                  'distance' => 140,
                  'tonnage' => 6.0,
                  'price' => 1808,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 72,
                  'distance' => 160,
                  'tonnage' => 6.0,
                  'price' => 1921,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 73,
                  'distance' => 180,
                  'tonnage' => 6.0,
                  'price' => 2034,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 74,
                  'distance' => 200,
                  'tonnage' => 6.0,
                  'price' => 2147,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 75,
                  'distance' => 220,
                  'tonnage' => 6.0,
                  'price' => 2260,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 76,
                  'distance' => 25,
                  'tonnage' => 7.0,
                  'price' => 661,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 77,
                  'distance' => 30,
                  'tonnage' => 7.0,
                  'price' => 771,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 78,
                  'distance' => 40,
                  'tonnage' => 7.0,
                  'price' => 881,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 79,
                  'distance' => 50,
                  'tonnage' => 7.0,
                  'price' => 992,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 80,
                  'distance' => 60,
                  'tonnage' => 7.0,
                  'price' => 1102,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 81,
                  'distance' => 70,
                  'tonnage' => 7.0,
                  'price' => 1212,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 82,
                  'distance' => 80,
                  'tonnage' => 7.0,
                  'price' => 1322,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 83,
                  'distance' => 90,
                  'tonnage' => 7.0,
                  'price' => 1432,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 84,
                  'distance' => 100,
                  'tonnage' => 7.0,
                  'price' => 1542,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 85,
                  'distance' => 120,
                  'tonnage' => 7.0,
                  'price' => 1653,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 86,
                  'distance' => 140,
                  'tonnage' => 7.0,
                  'price' => 1763,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 87,
                  'distance' => 160,
                  'tonnage' => 7.0,
                  'price' => 1873,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 88,
                  'distance' => 180,
                  'tonnage' => 7.0,
                  'price' => 1983,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 89,
                  'distance' => 200,
                  'tonnage' => 7.0,
                  'price' => 2093,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 90,
                  'distance' => 220,
                  'tonnage' => 7.0,
                  'price' => 2203,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 91,
                  'distance' => 25,
                  'tonnage' => 8.0,
                  'price' => 644,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 92,
                  'distance' => 30,
                  'tonnage' => 8.0,
                  'price' => 751,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 93,
                  'distance' => 40,
                  'tonnage' => 8.0,
                  'price' => 858,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 94,
                  'distance' => 50,
                  'tonnage' => 8.0,
                  'price' => 967,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 95,
                  'distance' => 60,
                  'tonnage' => 8.0,
                  'price' => 1074,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 96,
                  'distance' => 70,
                  'tonnage' => 8.0,
                  'price' => 1181,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 97,
                  'distance' => 80,
                  'tonnage' => 8.0,
                  'price' => 1288,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 98,
                  'distance' => 90,
                  'tonnage' => 8.0,
                  'price' => 1395,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 99,
                  'distance' => 100,
                  'tonnage' => 8.0,
                  'price' => 1502,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 100,
                  'distance' => 120,
                  'tonnage' => 8.0,
                  'price' => 1611,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 101,
                  'distance' => 140,
                  'tonnage' => 8.0,
                  'price' => 1718,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 102,
                  'distance' => 160,
                  'tonnage' => 8.0,
                  'price' => 1825,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 103,
                  'distance' => 180,
                  'tonnage' => 8.0,
                  'price' => 1932,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 104,
                  'distance' => 200,
                  'tonnage' => 8.0,
                  'price' => 2039,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 105,
                  'distance' => 220,
                  'tonnage' => 8.0,
                  'price' => 2146,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 106,
                  'distance' => 25,
                  'tonnage' => 9.0,
                  'price' => 627,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 107,
                  'distance' => 30,
                  'tonnage' => 9.0,
                  'price' => 731,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 108,
                  'distance' => 40,
                  'tonnage' => 9.0,
                  'price' => 835,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 109,
                  'distance' => 50,
                  'tonnage' => 9.0,
                  'price' => 942,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 110,
                  'distance' => 60,
                  'tonnage' => 9.0,
                  'price' => 1046,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 111,
                  'distance' => 70,
                  'tonnage' => 9.0,
                  'price' => 1150,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 112,
                  'distance' => 80,
                  'tonnage' => 9.0,
                  'price' => 1254,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 113,
                  'distance' => 90,
                  'tonnage' => 9.0,
                  'price' => 1358,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 114,
                  'distance' => 100,
                  'tonnage' => 9.0,
                  'price' => 1462,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 115,
                  'distance' => 120,
                  'tonnage' => 9.0,
                  'price' => 1569,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 116,
                  'distance' => 140,
                  'tonnage' => 9.0,
                  'price' => 1673,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 117,
                  'distance' => 160,
                  'tonnage' => 9.0,
                  'price' => 1777,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 118,
                  'distance' => 180,
                  'tonnage' => 9.0,
                  'price' => 1881,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 119,
                  'distance' => 200,
                  'tonnage' => 9.0,
                  'price' => 1985,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 120,
                  'distance' => 220,
                  'tonnage' => 9.0,
                  'price' => 2089,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 121,
                  'distance' => 25,
                  'tonnage' => 10.0,
                  'price' => 610,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 122,
                  'distance' => 30,
                  'tonnage' => 10.0,
                  'price' => 711,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 123,
                  'distance' => 40,
                  'tonnage' => 10.0,
                  'price' => 812,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 124,
                  'distance' => 50,
                  'tonnage' => 10.0,
                  'price' => 917,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 125,
                  'distance' => 60,
                  'tonnage' => 10.0,
                  'price' => 1018,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 126,
                  'distance' => 70,
                  'tonnage' => 10.0,
                  'price' => 1119,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 127,
                  'distance' => 80,
                  'tonnage' => 10.0,
                  'price' => 1220,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 128,
                  'distance' => 90,
                  'tonnage' => 10.0,
                  'price' => 1321,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 129,
                  'distance' => 100,
                  'tonnage' => 10.0,
                  'price' => 1422,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 130,
                  'distance' => 120,
                  'tonnage' => 10.0,
                  'price' => 1527,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 131,
                  'distance' => 140,
                  'tonnage' => 10.0,
                  'price' => 1628,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 132,
                  'distance' => 160,
                  'tonnage' => 10.0,
                  'price' => 1729,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 133,
                  'distance' => 180,
                  'tonnage' => 10.0,
                  'price' => 1830,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 134,
                  'distance' => 200,
                  'tonnage' => 10.0,
                  'price' => 1931,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 135,
                  'distance' => 220,
                  'tonnage' => 10.0,
                  'price' => 2032,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 136,
                  'distance' => 25,
                  'tonnage' => 11.0,
                  'price' => 593,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 137,
                  'distance' => 30,
                  'tonnage' => 11.0,
                  'price' => 691,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 138,
                  'distance' => 40,
                  'tonnage' => 11.0,
                  'price' => 789,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 139,
                  'distance' => 50,
                  'tonnage' => 11.0,
                  'price' => 892,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 140,
                  'distance' => 60,
                  'tonnage' => 11.0,
                  'price' => 990,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 141,
                  'distance' => 70,
                  'tonnage' => 11.0,
                  'price' => 1088,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 142,
                  'distance' => 80,
                  'tonnage' => 11.0,
                  'price' => 1186,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 143,
                  'distance' => 90,
                  'tonnage' => 11.0,
                  'price' => 1284,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 144,
                  'distance' => 100,
                  'tonnage' => 11.0,
                  'price' => 1382,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 145,
                  'distance' => 120,
                  'tonnage' => 11.0,
                  'price' => 1485,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 146,
                  'distance' => 140,
                  'tonnage' => 11.0,
                  'price' => 1583,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 147,
                  'distance' => 160,
                  'tonnage' => 11.0,
                  'price' => 1681,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 148,
                  'distance' => 180,
                  'tonnage' => 11.0,
                  'price' => 1779,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 149,
                  'distance' => 200,
                  'tonnage' => 11.0,
                  'price' => 1877,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 150,
                  'distance' => 220,
                  'tonnage' => 11.0,
                  'price' => 1975,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 151,
                  'distance' => 25,
                  'tonnage' => 12.0,
                  'price' => 576,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 152,
                  'distance' => 30,
                  'tonnage' => 12.0,
                  'price' => 671,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 153,
                  'distance' => 40,
                  'tonnage' => 12.0,
                  'price' => 766,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 154,
                  'distance' => 50,
                  'tonnage' => 12.0,
                  'price' => 867,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 155,
                  'distance' => 60,
                  'tonnage' => 12.0,
                  'price' => 962,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 156,
                  'distance' => 70,
                  'tonnage' => 12.0,
                  'price' => 1057,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 157,
                  'distance' => 80,
                  'tonnage' => 12.0,
                  'price' => 1152,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 158,
                  'distance' => 90,
                  'tonnage' => 12.0,
                  'price' => 1247,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 159,
                  'distance' => 100,
                  'tonnage' => 12.0,
                  'price' => 1342,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 160,
                  'distance' => 120,
                  'tonnage' => 12.0,
                  'price' => 1443,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 161,
                  'distance' => 140,
                  'tonnage' => 12.0,
                  'price' => 1538,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 162,
                  'distance' => 160,
                  'tonnage' => 12.0,
                  'price' => 1633,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 163,
                  'distance' => 180,
                  'tonnage' => 12.0,
                  'price' => 1728,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 164,
                  'distance' => 200,
                  'tonnage' => 12.0,
                  'price' => 1823,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 165,
                  'distance' => 220,
                  'tonnage' => 12.0,
                  'price' => 1918,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 166,
                  'distance' => 25,
                  'tonnage' => 13.0,
                  'price' => 559,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 167,
                  'distance' => 30,
                  'tonnage' => 13.0,
                  'price' => 651,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 168,
                  'distance' => 40,
                  'tonnage' => 13.0,
                  'price' => 743,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 169,
                  'distance' => 50,
                  'tonnage' => 13.0,
                  'price' => 842,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 170,
                  'distance' => 60,
                  'tonnage' => 13.0,
                  'price' => 934,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 171,
                  'distance' => 70,
                  'tonnage' => 13.0,
                  'price' => 1026,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 172,
                  'distance' => 80,
                  'tonnage' => 13.0,
                  'price' => 1118,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 173,
                  'distance' => 90,
                  'tonnage' => 13.0,
                  'price' => 1210,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 174,
                  'distance' => 100,
                  'tonnage' => 13.0,
                  'price' => 1302,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 175,
                  'distance' => 120,
                  'tonnage' => 13.0,
                  'price' => 1401,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 176,
                  'distance' => 140,
                  'tonnage' => 13.0,
                  'price' => 1493,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 177,
                  'distance' => 160,
                  'tonnage' => 13.0,
                  'price' => 1585,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 178,
                  'distance' => 180,
                  'tonnage' => 13.0,
                  'price' => 1677,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 179,
                  'distance' => 200,
                  'tonnage' => 13.0,
                  'price' => 1769,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 180,
                  'distance' => 220,
                  'tonnage' => 13.0,
                  'price' => 1861,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 181,
                  'distance' => 25,
                  'tonnage' => 14.0,
                  'price' => 542,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 182,
                  'distance' => 30,
                  'tonnage' => 14.0,
                  'price' => 631,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 183,
                  'distance' => 40,
                  'tonnage' => 14.0,
                  'price' => 720,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 184,
                  'distance' => 50,
                  'tonnage' => 14.0,
                  'price' => 817,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 185,
                  'distance' => 60,
                  'tonnage' => 14.0,
                  'price' => 906,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 186,
                  'distance' => 70,
                  'tonnage' => 14.0,
                  'price' => 995,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 187,
                  'distance' => 80,
                  'tonnage' => 14.0,
                  'price' => 1084,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 188,
                  'distance' => 90,
                  'tonnage' => 14.0,
                  'price' => 1173,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 189,
                  'distance' => 100,
                  'tonnage' => 14.0,
                  'price' => 1262,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 190,
                  'distance' => 120,
                  'tonnage' => 14.0,
                  'price' => 1359,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 191,
                  'distance' => 140,
                  'tonnage' => 14.0,
                  'price' => 1448,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 192,
                  'distance' => 160,
                  'tonnage' => 14.0,
                  'price' => 1537,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 193,
                  'distance' => 180,
                  'tonnage' => 14.0,
                  'price' => 1626,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 194,
                  'distance' => 200,
                  'tonnage' => 14.0,
                  'price' => 1715,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 195,
                  'distance' => 220,
                  'tonnage' => 14.0,
                  'price' => 1804,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 196,
                  'distance' => 25,
                  'tonnage' => 20.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 197,
                  'distance' => 30,
                  'tonnage' => 20.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 198,
                  'distance' => 40,
                  'tonnage' => 20.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 199,
                  'distance' => 50,
                  'tonnage' => 20.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 200,
                  'distance' => 60,
                  'tonnage' => 20.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 201,
                  'distance' => 70,
                  'tonnage' => 20.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 202,
                  'distance' => 80,
                  'tonnage' => 20.0,
                  'price' => 950,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 203,
                  'distance' => 90,
                  'tonnage' => 20.0,
                  'price' => 1000,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 204,
                  'distance' => 100,
                  'tonnage' => 20.0,
                  'price' => 1050,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 205,
                  'distance' => 120,
                  'tonnage' => 20.0,
                  'price' => 1100,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 206,
                  'distance' => 140,
                  'tonnage' => 20.0,
                  'price' => 1150,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 207,
                  'distance' => 160,
                  'tonnage' => 20.0,
                  'price' => 1200,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 208,
                  'distance' => 180,
                  'tonnage' => 20.0,
                  'price' => 1250,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 209,
                  'distance' => 200,
                  'tonnage' => 20.0,
                  'price' => 1325,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 210,
                  'distance' => 220,
                  'tonnage' => 20.0,
                  'price' => 1400,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 211,
                  'distance' => 25,
                  'tonnage' => 21.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 212,
                  'distance' => 30,
                  'tonnage' => 21.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 213,
                  'distance' => 40,
                  'tonnage' => 21.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 214,
                  'distance' => 50,
                  'tonnage' => 21.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 215,
                  'distance' => 60,
                  'tonnage' => 21.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 216,
                  'distance' => 70,
                  'tonnage' => 21.0,
                  'price' => 900,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 217,
                  'distance' => 80,
                  'tonnage' => 21.0,
                  'price' => 950,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 218,
                  'distance' => 90,
                  'tonnage' => 21.0,
                  'price' => 1000,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 219,
                  'distance' => 100,
                  'tonnage' => 21.0,
                  'price' => 1050,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 220,
                  'distance' => 120,
                  'tonnage' => 21.0,
                  'price' => 1100,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 221,
                  'distance' => 140,
                  'tonnage' => 21.0,
                  'price' => 1150,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 222,
                  'distance' => 160,
                  'tonnage' => 21.0,
                  'price' => 1200,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 223,
                  'distance' => 180,
                  'tonnage' => 21.0,
                  'price' => 1250,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 224,
                  'distance' => 200,
                  'tonnage' => 21.0,
                  'price' => 1325,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 225,
                  'distance' => 220,
                  'tonnage' => 21.0,
                  'price' => 1400,
                  'transport_type_id' => 3
                ),
                array(
                  'id' => 226,
                  'distance' => 25,
                  'tonnage' => 15.0,
                  'price' => 500,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 227,
                  'distance' => 30,
                  'tonnage' => 15.0,
                  'price' => 600,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 228,
                  'distance' => 40,
                  'tonnage' => 15.0,
                  'price' => 700,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 229,
                  'distance' => 50,
                  'tonnage' => 15.0,
                  'price' => 800,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 230,
                  'distance' => 60,
                  'tonnage' => 15.0,
                  'price' => 900,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 231,
                  'distance' => 70,
                  'tonnage' => 15.0,
                  'price' => 1000,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 232,
                  'distance' => 80,
                  'tonnage' => 15.0,
                  'price' => 1100,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 233,
                  'distance' => 90,
                  'tonnage' => 15.0,
                  'price' => 1200,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 234,
                  'distance' => 100,
                  'tonnage' => 15.0,
                  'price' => 1300,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 235,
                  'distance' => 120,
                  'tonnage' => 15.0,
                  'price' => 1400,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 236,
                  'distance' => 140,
                  'tonnage' => 15.0,
                  'price' => 1500,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 237,
                  'distance' => 160,
                  'tonnage' => 15.0,
                  'price' => 1500,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 238,
                  'distance' => 180,
                  'tonnage' => 15.0,
                  'price' => 1600,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 239,
                  'distance' => 200,
                  'tonnage' => 15.0,
                  'price' => 1700,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 240,
                  'distance' => 220,
                  'tonnage' => 15.0,
                  'price' => 1800,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 241,
                  'distance' => 15,
                  'tonnage' => 8.0,
                  'price' => 500,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 242,
                  'distance' => 20,
                  'tonnage' => 8.0,
                  'price' => 600,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 243,
                  'distance' => 25,
                  'tonnage' => 8.0,
                  'price' => 700,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 244,
                  'distance' => 30,
                  'tonnage' => 8.0,
                  'price' => 800,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 245,
                  'distance' => 35,
                  'tonnage' => 8.0,
                  'price' => 900,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 246,
                  'distance' => 40,
                  'tonnage' => 8.0,
                  'price' => 1000,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 247,
                  'distance' => 50,
                  'tonnage' => 8.0,
                  'price' => 1200,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 248,
                  'distance' => 60,
                  'tonnage' => 8.0,
                  'price' => 1400,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 249,
                  'distance' => 70,
                  'tonnage' => 8.0,
                  'price' => 1650,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 250,
                  'distance' => 80,
                  'tonnage' => 8.0,
                  'price' => 1900,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 251,
                  'distance' => 90,
                  'tonnage' => 8.0,
                  'price' => 2100,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 252,
                  'distance' => 100,
                  'tonnage' => 8.0,
                  'price' => 2300,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 253,
                  'distance' => 120,
                  'tonnage' => 8.0,
                  'price' => 2700,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 254,
                  'distance' => 140,
                  'tonnage' => 8.0,
                  'price' => 3100,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 255,
                  'distance' => 160,
                  'tonnage' => 8.0,
                  'price' => 3500,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 256,
                  'distance' => 180,
                  'tonnage' => 8.0,
                  'price' => 3900,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 257,
                  'distance' => 200,
                  'tonnage' => 8.0,
                  'price' => 4400,
                  'transport_type_id' => 2
                ),
                array(
                  'id' => 258,
                  'distance' => 35,
                  'tonnage' => 6.0,
                  'price' => 847,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 259,
                  'distance' => 35,
                  'tonnage' => 7.0,
                  'price' => 826,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 260,
                  'distance' => 35,
                  'tonnage' => 8.0,
                  'price' => 805,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 261,
                  'distance' => 35,
                  'tonnage' => 9.0,
                  'price' => 784,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 262,
                  'distance' => 35,
                  'tonnage' => 10.0,
                  'price' => 763,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 263,
                  'distance' => 35,
                  'tonnage' => 11.0,
                  'price' => 742,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 264,
                  'distance' => 35,
                  'tonnage' => 12.0,
                  'price' => 721,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 265,
                  'distance' => 35,
                  'tonnage' => 13.0,
                  'price' => 700,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 266,
                  'distance' => 35,
                  'tonnage' => 14.0,
                  'price' => 679,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 267,
                  'distance' => 35,
                  'tonnage' => 15.0,
                  'price' => 700,
                  'transport_type_id' => 4
                ),
                array(
                  'id' => 268,
                  'distance' => 35,
                  'tonnage' => 3.0,
                  'price' => 1133,
                  'transport_type_id' => 5
                )
            ]
        );
    }
}
