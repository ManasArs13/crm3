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
            $table->string('name',190)->nullable()->index();
            $table->string('description')->nullable()->index();
            $table->string('shipment_address')->nullable()->index();
            $table->foreignId('order_id')->nullable()->references('id')->on('orders')->onDelete('cascade');
            $table->string('counterparty_link')->nullable();
            $table->string('service_link')->nullable();
            $table->integer('paid_sum')->default(0.00)->index();
            $table->integer('suma')->index();
            $table->string('status', 190)->nullable()->index();
            $table->foreignId("delivery_id")->nullable()->references('id')->on("deliveries");
            $table->foreignId("contact_id")->nullable()->references('id')->on("contacts");
            $table->foreignId("carrier_id")->nullable()->references('id')->on("carriers");
            $table->integer('delivery_price')->nullable()->index();
            $table->integer('delivery_price_norm')->nullable()->index();
            $table->integer('delivery_fee')->nullable()->index();
            $table->integer('saldo')->nullable()->index();
            $table->foreignId('transport_id')->nullable()->references('id')->on('transports');
            $table->foreignId('transport_type_id')->nullable()->references('id')->on('transport_types');
            $table->timestamps();
            $table->decimal('weight', 10, 0);
            $table->char('ms_id', 36)->nullable();
            $table->softDeletes('deleted_at', 0);
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
