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
        Schema::create('contact_amo_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contact_id')->references('id')->on('contacts');
            $table->foreignId('contact_amo_id')->references('id')->on('contact_amos');
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_amo_contacts');
    }
};
