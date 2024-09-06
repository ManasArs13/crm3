<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {

        DB::table('model_has_roles')->insert([
            ['role_id' => '1','model_type' => 'App\Models\User', 'model_id' => '1'],
        ]);
    }
}
