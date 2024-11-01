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
        Schema::create('enter_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->nullable()->index()->constrained("products");
            $table->foreignId("enter_id")->index()->constrained("enters")->onDelete('cascade');
            $table->unsignedDecimal("quantity", 10, 1)->default(0)->index();
            $table->integer("price")->unsigned()->default(0)->index();
            $table->integer("sum")->unsigned()->default(0)->index();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enter_positions');
    }
};
