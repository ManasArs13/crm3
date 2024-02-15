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
        Schema::create('order_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->nullable()->index()->constrained("products");
            $table->foreignId("order_id")->index()->constrained("orders");

            $table->integer("quantity")->unsigned()->default(0);
            $table->integer("shipped")->unsigned()->default(0);
            $table->integer("reserve")->unsigned()->default(0);
            $table->integer("price")->unsigned()->default(0);

            $table->integer("count_pallets")->unsigned()->default(0)->nullable();
            $table->unsignedDecimal("weight_kg", 8, 1)->unsigned()->nullable();

            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_positions');
    }
};
