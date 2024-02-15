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
        Schema::create('tech_process_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processing_id')->on('processings')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('product_id')->on('products')->onDelete('cascade')->cascadeOnUpdate();
            $table->unsignedDecimal('quantity', 8, 1);
            $table->unsignedDecimal('quantity_norm', 8, 1)->nullable();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_process_materials');
    }
};
