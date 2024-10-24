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
            $table->string("amo_id",190)->index()->comment('ид в амо');
            $table->string("phone",56)->nullable()->index();
            $table->string("contact_amo_id")->nullable()->references('id')->on("contact_amos");
            $table->foreignId("employee_amo_id")->nullable()->references('id')->on("employee_amos");
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
