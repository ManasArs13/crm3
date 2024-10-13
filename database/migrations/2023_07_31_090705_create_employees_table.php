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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name", 190)->nullable();
            $table->string("firstName", 190)->nullable();
            $table->string("middleName", 190)->nullable();
            $table->string("lastName", 190)->nullable();
            $table->string("fullName", 190)->nullable();
            $table->string("shortFio", 190)->nullable();
            $table->string("position", 190)->nullable();
            $table->text("email")->nullable();
            $table->string("phone", 56)->nullable();
            $table->integer("salary")->default(0);
            $table->string("uid", 190)->nullable();
            $table->boolean('archived')->default(false);
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
