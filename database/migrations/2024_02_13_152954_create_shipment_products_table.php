<?php

use App\Models\Shipment;
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
        Schema::create('shipment_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal("quantity", 10, 1)->default(0)->index();
            $table->foreignId("shipment_id")->nullable()->index()->constrained("shipments")->onDelete('cascade');
            $table->foreignId("product_id")->nullable()->index()->constrained("products");
            $table->integer('price_norm')->default(0)->nullable()->index();
            $table->integer("price")->unsigned()->default(0)->index();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_products');
    }
};
