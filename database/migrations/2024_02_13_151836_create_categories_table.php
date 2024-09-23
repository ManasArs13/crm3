<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190);
            $table->string("short_name", 190);
            $table->boolean('is_active')->default(0);
            $table->enum('type', [
                Category::NOT_SELECTED,
                Category::MATERIAL,
                Category::PRODUCTS
            ]);
            $table->enum('building_material', [
                Category::NOT_SELECTED,
                Category::CONCRETE,
                Category::BLOCK
            ]);
            $table->string('sort')->nullable();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
