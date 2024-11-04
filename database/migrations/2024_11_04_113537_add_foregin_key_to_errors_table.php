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
        Schema::table('errors', function (Blueprint $table) {
            $table->unsignedBigInteger('responsible_user')->nullable()->change();
            $table->foreign('responsible_user')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('errors', function (Blueprint $table) {
            //
        });
    }
};
