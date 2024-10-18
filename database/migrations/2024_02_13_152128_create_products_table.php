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
            $table->string("name", 190)->index();
            $table->string("short_name", 190)->nullable()->index();
            $table->integer("price")->default(0)->index();
            $table->integer("balance")->default(0)->index();
            $table->decimal("weight_kg", 8, 1)->default(0.0)->index();
            $table->integer("count_pallets", false, true)->default(0)->index();
            $table->integer("pieces_cycle")->nullable();
            $table->foreignId("category_id")->nullable()->index()->constrained("categories");
            $table->foreignId("color_id")->nullable()->index()->constrained("colors");
            $table->boolean("is_active")->default(0)->index();
            $table->enum('materials', [
                'не указано',
                'нет',
                'да'
            ])->default('не указано')->index();
            $table->integer("min_balance")->default(0)->index();
            $table->enum('type', [
                Product::NOT_SELECTED,
                Product::MATERIAL,
                Product::PRODUCTS
            ])->index();
            $table->integer('residual_norm')->nullable()->index();
            $table->integer('consumption_year')->nullable()->index();
            $table->enum('building_material', [
                Product::NOT_SELECTED,
                Product::CONCRETE,
                Product::BLOCK,
                Product::DELIVERY
            ])->default(Product::NOT_SELECTED)->index();
            $table->integer('residual')->nullable()->index();
            $table->integer('release')->nullable()->index();
            $table->string('sort')->nullable()->index();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable()->index();
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
