<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dateTime("moment_at")->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            //
        });
    }
};
