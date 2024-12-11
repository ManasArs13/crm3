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
        Schema::table('deliveries', function (Blueprint $table) {
            $table->renameColumn('ton_price', 'betonprice');
            $table->integer('manipulatorprice');
            $table->integer('gazelleprice');
            $table->integer('tralprice');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->renameColumn( 'betonprice', 'ton_price');
            $table->dropColumn('manipulatorprice');
            $table->dropColumn('gazelleprice');
            $table->dropColumn('tralprice');
        });
    }
};
