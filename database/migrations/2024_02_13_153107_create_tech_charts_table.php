<?php

use App\Models\TechChart;
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
        Schema::create('tech_charts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->unsignedDecimal('cost', 8, 2)->nullable();
            $table->enum('group',[
                TechChart::CONCRETE, TechChart::PRESS
            ])->nullable();
            $table->boolean('archived')->default(false);
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_charts');
    }
};
