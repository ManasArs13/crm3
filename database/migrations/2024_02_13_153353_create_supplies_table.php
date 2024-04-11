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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name",190)->nullable();
            $table->foreignId("contact_id")->nullable()->index()->constrained("contacts");
            $table->dateTime("moment")->nullable();
            $table->text('description')->nullable();
            $table->decimal("sum", 10, 2)->default(0.0);
            $table->string("incoming_number", 190)->nullable();
            $table->dateTime("incoming_date")->nullable();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
