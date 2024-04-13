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
        Schema::create('order_amos', function (Blueprint $table) {
            $table->id();
            $table->string("name",190);
            $table->foreignId("status_amo_id")->nullable()->index()->constrained("status_amos");
            $table->foreignId("contact_amo_id")->nullable()->index()->constrained("contact_amos");
            $table->foreignId("contact_amo2_id")->nullable()->index()->constrained("contact_amos");
            $table->integer("price")->unsigned()->nullable();
            $table->string("comment")->nullable();
            $table->boolean("is_exist")->default(0);
            $table->string("order_link")->nullable();
            $table->string("order_id")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_amos');
    }
};
