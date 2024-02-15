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
        Schema::create('transport_types', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190);
            $table->boolean("is_manipulator")->default(0);
            $table->integer("unloading_price")->default(0);
            $table->integer("min_price")->default(0);
            $table->decimal("coefficient", 5, 1)->default(0.0);
            $table->integer("min_tonnage")->default(0);
            $table->integer("sort")->default(100)->nullable();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transport_types');
    }
};
