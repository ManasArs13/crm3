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
        Schema::create('correct_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->decimal('quantity', 10, 1)->unsigned()->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->unsignedInteger('sum')->nullable();
            $table->char('ms_id', 36)->nullable();
            $table->decimal('quantity_norm', 8, 1)->unsigned()->nullable();
            $table->unsignedBigInteger('correct_id');
            $table->unsignedBigInteger('enter_position_id')->nullable();
            $table->unsignedBigInteger('loss_position_id')->nullable();
            $table->unsignedBigInteger('supply_position_id')->nullable();
            $table->unsignedBigInteger('tech_process_material_id')->nullable();
            $table->unsignedBigInteger('tech_process_product_id')->nullable();

            $table->timestamps();


            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('correct_id')->references('id')->on('corrects')->onDelete('cascade');
            $table->foreign('enter_position_id')->references('id')->on('enter_positions')->onDelete('set null');
            $table->foreign('loss_position_id')->references('id')->on('loss_positions')->onDelete('set null');
            $table->foreign('supply_position_id')->references('id')->on('supply_positions')->onDelete('set null');
            $table->foreign('tech_process_material_id')->references('id')->on('tech_process_materials')->onDelete('set null');
            $table->foreign('tech_process_product_id')->references('id')->on('tech_process_products')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correct_positions');
    }
};
