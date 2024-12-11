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
        Schema::create('corrects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 190)->nullable();
            $table->text('description')->nullable();
            $table->datetime('moment')->nullable();
            $table->decimal('sum', 10, 2)->nullable();
            $table->char('ms_id', 36)->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->string('incoming_number', 190)->nullable();
            $table->datetime('incoming_date')->nullable();
            $table->decimal('quantity', 8, 1)->nullable();
            $table->integer('hours')->nullable();
            $table->integer('cycles')->nullable();
            $table->integer('defective')->nullable();
            $table->unsignedBigInteger('tech_chart_id')->nullable();


            $table->unsignedBigInteger('loss_id')->nullable();
            $table->unsignedBigInteger('supply_id')->nullable();
            $table->unsignedBigInteger('tech_process_id')->nullable();
            $table->unsignedBigInteger('enter_id')->nullable();

            $table->timestamps();

            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('tech_chart_id')->references('id')->on('tech_charts')->onDelete('set null');
            $table->foreign('loss_id')->references('id')->on('losses')->onDelete('set null');
            $table->foreign('supply_id')->references('id')->on('supplies')->onDelete('set null');
            $table->foreign('tech_process_id')->references('id')->on('tech_processes')->onDelete('set null');
            $table->foreign('enter_id')->references('id')->on('enters')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrects');
    }
};
