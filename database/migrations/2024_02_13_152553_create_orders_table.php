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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("name", 190)->nullable()->index();
            $table->foreignId("status_id")->nullable()->index()->constrained("statuses");
            $table->foreignId("contact_id")->nullable()->index()->constrained("contacts");
            $table->foreignId("manager_id")->nullable()->references('id')->on("managers");
            $table->foreignId("employee_id")->nullable()->references('id')->on("employees");
            $table->string("address")->nullable()->index();
            $table->dateTime("date_plan")->nullable()->index();
            $table->dateTime("date_moment")->nullable()->index();
            $table->decimal("sum", 10, 1)->default(0.0)->index();
            $table->decimal("payed_sum", 10, 1)->default(0.0)->index();
            $table->decimal("shipped_sum", 10,1)->default(0.0)->index();
            $table->decimal("reserved_sum", 10,1)->default(0.0)->index();
            $table->decimal("weight", 10,1)->default(0.0)->index();
            $table->integer("count_pallets")->default(0)->index();
            $table->integer("norm1_price")->default(0.0)->index();
            $table->integer("norm2_price")->default(0.0)->index();
            $table->foreignId("transport_id")->nullable()->index()->constrained("transports");
            $table->foreignId("delivery_id")->nullable()->index()->constrained("deliveries");
            $table->foreignId("transport_type_id")->nullable()->index()->constrained("transport_types");
            $table->integer("delivery_price")->default(0.0)->index();
            $table->boolean("is_demand")->default(0)->index();
            $table->boolean("is_made")->default(0)->index();
            $table->boolean("status_shipped")->default(0)->index();
            $table->decimal("debt", 10, 1)->default(0.0);
            $table->string("order_amo_link")->nullable();
            $table->string("order_amo_id")->nullable();
            $table->text("comment")->nullable();
            $table->integer('delivery_price_norm')->nullable()->index();
            $table->boolean('late')->default(0)->nullable();
            $table->timestamps();
            $table->char('ms_id', 36)->nullable()->index();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
