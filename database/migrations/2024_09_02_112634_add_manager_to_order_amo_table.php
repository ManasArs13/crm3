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
        Schema::table('order_amos', function (Blueprint $table) {
            $table->foreignId("manager_id")->nullable()->references('id')->on("managers");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_amos', function (Blueprint $table) {
            $table->dropForeign('manager_id');
        });
    }
};
