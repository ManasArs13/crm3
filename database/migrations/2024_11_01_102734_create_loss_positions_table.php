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
        Schema::create('loss_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_id")->nullable()->index()->constrained("products");
            $table->foreignId("loss_id")->index()->constrained("losses")->onDelete('cascade');
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
        Schema::dropIfExists('loss_positions');
    }
};
