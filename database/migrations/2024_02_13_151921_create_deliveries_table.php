<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190)->index();
            $table->integer("distance")->nullable()->index();
            $table->integer("ton_price")->nullable()->index();
            $table->integer('km_fact')->index();
            $table->integer("time_minute")->index();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
