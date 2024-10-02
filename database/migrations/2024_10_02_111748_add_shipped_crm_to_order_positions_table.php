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
        Schema::table('order_positions', function (Blueprint $table) {
            $table->unsignedDecimal("shipped_crm", 10, 1)->unsigned()->nullable()->default(0);
            $table->unsignedDecimal("quantity", 10, 1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_positions', function (Blueprint $table) {
            //
        });
    }
};
