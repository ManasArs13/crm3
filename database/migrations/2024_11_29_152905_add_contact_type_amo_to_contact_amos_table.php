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
        Schema::table('contact_amos', function (Blueprint $table) {
            $table->foreignId("contact_type_amo_id")->nullable()->references('id')->on("contact_type_amos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_amos', function (Blueprint $table) {
            $table->dropIndex("contact_type_amo_id");
        });
    }
};
