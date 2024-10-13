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
            $table->foreignId("order_id")->index()->constrained("orders")->onDelete('cascade');
            $table->unsignedDecimal("quantity", 10, 1)->default(0)->index();
            $table->integer("shipped")->unsigned()->default(0)->index();
            $table->unsignedDecimal("shipped_crm", 10, 1)->default(0)->index();
            $table->integer("reserve")->unsigned()->default(0)->index();
            $table->integer("price")->unsigned()->default(0)->index();
            $table->integer("count_pallets")->unsigned()->default(0)->nullable()->index();
            $table->unsignedDecimal("weight_kg", 10, 1)->unsigned()->nullable()->index();
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
