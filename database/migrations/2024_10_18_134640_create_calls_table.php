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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->string("amo_id",190)->index()->comment('ид в амо');
            $table->string("duration",190)->index()->nullable()->comment('длительность, c');
            $table->foreignId("employee_amo_id")->nullable()->references('id')->on("employee_amos")->comment('менеджер');
            $table->enum('type', ['incoming_call', 'outgoing_call'])->comment('входящий/исходящий');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
