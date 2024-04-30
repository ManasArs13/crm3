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
        Schema::create('outgoings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("contact_id")->nullable()->constrained("contacts");
            $table->dateTime("moment")->nullable();
            $table->text('description')->nullable();
            $table->decimal("sum", 10, 2)->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outgoings');
    }
};
