<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190);
            $table->string("hex", 6)->default(000000)->nullable(true);
            $table->string("font_color", 6)->default(000000)->nullable(true);
            $table->timestamps();
            $table->char('ms_id', 36)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
