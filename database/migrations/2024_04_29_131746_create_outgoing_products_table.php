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
        Schema::create('outgoing_products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('outgoing_id')->on('outgoings')->onDelete('cascade')->cascadeOnUpdate();
            $table->foreignId('product_id')->on('products')->onDelete('cascade')->cascadeOnUpdate();
            $table->unsignedDecimal('quantity', 8, 1);
            $table->decimal("price", 10, 2)->default(0.0);
            $table->decimal("summa", 10, 2)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoing_products');
    }
};
