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
        Schema::create('contact_contact_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_category_id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_category_id')->references('id')->on('contact_categories');
            $table->foreign('contact_id')->references('id')->on('contacts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_contact_category');
    }
};
