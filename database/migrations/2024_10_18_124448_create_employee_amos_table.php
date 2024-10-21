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
        Schema::create('employee_amos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("amo_id",190)->index()->comment('ид в амо');
            $table->string("name", 190)->nullable()->comment('имя');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_amos');
    }
};
