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
        DB::table('users')->insert(
            [
                'name' => 'Administrator',
                'email' => 'admin@euroblock83.ru',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Administrator2',
                'email' => 'admin2@euroblock83.ru',
                'email_verified_at' => now(),
                'password' => Hash::make('admin'),
                'remember_token' => Str::random(10),
            ],
        );
    }
}
