<?php

use App\Models\Product;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190);
            $table->integer("price")->default(0);
            $table->integer("balance")->default(0);
            $table->decimal("weight_kg", 8, 1)->default(0.0);
            $table->integer("count_pallets", false, true)->default(0);
            $table->foreignId("category_id")->nullable()->index()->constrained("categories");
            $table->foreignId("color_id")->nullable()->index()->constrained("colors");
            $table->boolean("is_active")->default(0);
            $table->enum('materials', [
                'не указано',
                'нет',
                'да'
            ])->default('не указано');
            $table->integer("min_balance")->default(0);
            $table->enum('type', [
                Product::NOT_SELECTED,
                Product::MATERIAL,
                Product::PRODUCTS
            ]);
            $table->integer('residual_norm')->nullable();
            $table->integer('consumption_year')->nullable();
            $table->enum('building_material', [
                Product::NOT_SELECTED,
                Product::CONCRETE,
                Product::BLOCK,
                Product::DELIVERY
            ])->default(Product::NOT_SELECTED);
            $table->integer('residual')->nullable();
            $table->integer('release')->nullable();
            $table->string('sort')->nullable();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
