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
            $table->string("name", 190)->index();
            $table->string("hex", 6)->default(000000)->nullable(true)->index();
            $table->text("font_color")->nullable();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
};
