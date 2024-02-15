<?php

use App\Models\Shipment;
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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('name',190)->nullable();
            $table->string('description')->nullable();
            $table->string('shipment_address')->nullable();
            $table->foreignId('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            $table->string('counterparty_link')->nullable();
            $table->string('service_link')->nullable();
            $table->integer('paid_sum')->default(0.00);
            $table->integer('suma');
            $table->enum('status',[
                Shipment::APPOINTED,
                Shipment::NOT_PAID,
                Shipment::PAID
            ])->nullable();
            $table->foreignId("delivery_id")->nullable()->index()->constrained("deliveries");
            $table->integer('delivery_price')->nullable();
            $table->integer('delivery_price_norm')->nullable();
            $table->integer('delivery_fee')->nullable();
            $table->foreignId('transport_id')->nullable()->references('id')->on('transports');
            $table->foreignId('transport_type_id')->nullable()->references('id')->on('transport_types');
            $table->timestamps();
            $table->decimal('weight', 10, 0);
            $table->char('ms_id', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};
