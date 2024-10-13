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
            $table->string("name", 190)->index();
            $table->boolean("is_manipulator")->default(0)->index();
            $table->integer("unloading_price")->default(0)->index();
            $table->integer("min_price")->default(0)->index();
            $table->decimal("coefficient", 5, 1)->default(0.0)->index();
            $table->integer("min_tonnage")->default(0)->index();
            $table->integer("sort")->default(100)->nullable();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable()->index();
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
