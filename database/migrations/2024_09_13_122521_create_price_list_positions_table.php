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
        Schema::create('price_list_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("price_list_id")->index()->constrained("price_lists");
            $table->foreignId("product_id")->nullable()->index()->constrained("products");

            $table->integer("price")->unsigned()->default(0);
            $table->char('ms_id', 36)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_list_positions');
    }
};
