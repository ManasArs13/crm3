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
        Schema::create('tech_processes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('moment', $precision = 0);
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->foreignId('tech_chart_id')->on('tech_carts')->nullable();
            $table->decimal('quantity', 8, 1)->nullable();
            $table->unsignedInteger('hours')->nullable();
            $table->unsignedInteger('cycles')->nullable();
            $table->unsignedInteger('defective')->nullable();  
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tech_processes');
    }
};
