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
        Schema::create('talk_amos', function (Blueprint $table) {
            $table->id();
            $table->string("name", 150)->nullable()->index();
            $table->string("phone",56)->nullable()->index();
            $table->string("contact_amo_id")->nullable();
            $table->foreignId("manager_id")->nullable()->references('id')->on("managers");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talk_amos');
    }
};
