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
        Schema::create('shiping_prices', function (Blueprint $table) {
            $table->id();
            $table->integer("distance")->unsigned()->index();
            $table->decimal("tonnage", 8, 1)->unsigned()->index();
            $table->integer("price")->unsigned()->index();
            $table->foreignId("transport_type_id")->index()->constrained("transport_types");
            $table->timestamps();
            $table->char('ms_id', 36)->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_prices');
    }
};
