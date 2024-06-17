<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Option;

class Option5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $opt=Option::where("code","ms_organization_guid")->first();
        $opt->name="Организация: Гуид организации";
        $opt->value= "03957745-4672-11ee-0a80-0dbe00139b20";
        $opt->save();
    }
}
