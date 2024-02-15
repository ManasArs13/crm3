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
        Schema::create('order_amo_order', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreignId('order_amo_id')->nullable()->references('id')->on('order_amos')->onDelete('cascade');
            $table->boolean('is_manual')->default(0);
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_amo_orders');
    }
};
