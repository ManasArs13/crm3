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
        Schema::create('tech_chart_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tech_chart_id')->on('tech_charts')->onDelete('cascade')->cascadeOnUpdate()->index();
            $table->foreignId('product_id')->on('products')->onDelete('cascade')->cascadeOnUpdate()->index();
            $table->unsignedDecimal('quantity', 8, 2)->index();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_chart_products');
    }
};
