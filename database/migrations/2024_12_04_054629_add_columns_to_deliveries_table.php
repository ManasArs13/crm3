<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->text("locality")->nullable();
            $table->text("region")->nullable();
            $table->string("type")->nullable();
            $table->decimal('population', 10, 3)->nullable();
            $table->string("coords")->nullable();
            $table->string("source")->nullable();
            $table->string("sourceID")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            //
        });
    }
};
