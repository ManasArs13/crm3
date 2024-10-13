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
        Schema::create('contact_amos', function (Blueprint $table) {
            $table->id();
            $table->string("name", 150)->nullable()->index();
            $table->string("phone",56)->nullable()->index();
            $table->string("phone1",56)->nullable()->index();
            $table->string("email")->nullable();
            $table->string("phone_norm", 56)->nullable()->index();
            $table->string("contact_ms_id")->nullable()->index();
            $table->string("contact_ms_link")->nullable();
            $table->boolean("is_exist")->default(0);
            $table->boolean("is_dublash")->default(0);
            $table->boolean("is_success")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_amos');
    }
};
