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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string("name",190);
            $table->string("phone", 56)->nullable();
            $table->string("phone_norm", 56)->nullable();
            $table->string("email", 100)->nullable();
            $table->string("contact_amo_id")->nullable();
            $table->string("contact_amo_link")->nullable();
            $table->decimal("balance",10,1)->default(0.0)->nullable();
            $table->boolean("is_exist")->default(0);
            $table->boolean("is_dublash")->default(0);
            $table->integer('is_archived')->nullable();
            $table->index('contact_amo_id');
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
