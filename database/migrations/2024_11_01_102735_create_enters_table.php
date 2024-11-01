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
        Schema::create('enters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("name", 190)->nullable()->index();
            $table->text('description')->nullable();
            $table->dateTime("moment")->nullable()->index();
            $table->decimal("sum", 10, 1)->default(0.0)->index();
            $table->char('ms_id', 36)->nullable();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enters');
    }
};
