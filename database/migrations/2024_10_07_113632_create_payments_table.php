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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190)->nullable()->index();
            $table->string("type", 190)->index();
            $table->string("operation", 190)->nullable()->index();
            $table->dateTime("moment")->nullable()->index();
            $table->text("description")->nullable();
            $table->foreignId("contact_id")->nullable()->index()->constrained("contacts");
            $table->decimal("sum", 10, 1)->default(0.0)->index();
            $table->char('ms_id', 36)->nullable()->index();
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymants');
    }
};
