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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190)->index();
            $table->string("description")->nullable()->index();
            $table->unsignedBigInteger('type_id')->nullable()->index();
            $table->foreign('type_id')->references('id')->on('transport_types')->onDelete('set null');
            $table->integer('tonnage')->nullable()->index();
            $table->foreignId("contact_id")->nullable()->references('id')->on("contacts");
            $table->char('phone')->nullable();
            $table->string("car_number")->nullable()->index();
            $table->string("driver")->nullable()->index();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
