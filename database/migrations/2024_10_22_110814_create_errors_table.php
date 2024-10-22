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
        Schema::create('errors', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->nullable()->default(0);
            $table->boolean('allowed')->nullable()->default(0);
            $table->foreignId("type_id")->nullable()->references('id')->on("error_types");
            $table->text("link")->nullable();
            $table->text("description")->nullable();
            $table->text("responsible_user")->nullable();
            $table->text("user_description")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errors');
    }
};
