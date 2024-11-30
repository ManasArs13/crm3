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
            $table->foreignId("source_id")->nullable()->references('id')->on("order_source_amos");
            $table->foreignId("reason_for_closure_id")->nullable()->references('id')->on("order_reason_for_closure_amos");
            $table->foreignId("employee_amo_id")->nullable()->references('id')->on("employee_amos");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_amos', function (Blueprint $table) {
            $table->dropConstrainedForeignId("source_id");
            $table->dropConstrainedForeignId("reason_for_closure_id");
            $table->dropConstrainedForeignId("employee_amo_id");
        });
    }
};
