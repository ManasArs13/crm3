<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'home', 'order', 'order_edit', 'payment', 'payment_edit', 'shipment', 'shipment_edit', 'residual',
            'techchart', 'techprocess', 'calculator', 'transporter_fee', 'contact', 'contact_edit', 'product',
            'product_edit', 'material', 'material_edit', 'transport', 'transport_edit', 'shift', 'shift_edit',
            'transport_type', 'transport_type_edit', 'delivery', 'delivery_edit', 'delivery_price', 'delivery_price_edit',
            'category_product', 'category_product_edit', 'error', 'error_type', 'error_type_edit', 'supply', 'supply_edit',
            'order_position', 'order_position_edit', 'shipment_position', 'shipment_position_edit', 'supply_position',
            'supply_position_edit', 'operator_order', 'operator_shipment', 'amo_order', 'amo_contact', 'amo_contact_edit',
            'contact_link', 'double_order', 'call', 'conversation', 'user', 'user_permission', 'report_manager', 'report_manager_two',
            'report_day', 'report_deviation', 'report_delivery_category', 'report_delivery', 'report_transport', 'report_transporter',
            'report_counterparty', 'report_summary', 'report_summary_remains', 'debtor', 'option', 'option_edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Permission::whereIn('name', ['home', 'order', 'order_edit', 'payment', 'payment_edit', 'shipment', 'shipment_edit', 'residual',
            'techchart', 'techprocess', 'calculator', 'transporter_fee', 'contact', 'contact_edit', 'product',
            'product_edit', 'material', 'material_edit', 'transport', 'transport_edit', 'shift', 'shift_edit',
            'transport_type', 'transport_type_edit', 'delivery', 'delivery_edit', 'delivery_price', 'delivery_price_edit',
            'category_product', 'category_product_edit', 'error', 'error_type', 'error_type_edit', 'supply', 'supply_edit',
            'order_position', 'order_position_edit', 'shipment_position', 'shipment_position_edit', 'supply_position',
            'supply_position_edit', 'operator_order', 'operator_shipment', 'amo_order', 'amo_contact', 'amo_contact_edit',
            'contact_link', 'double_order', 'call', 'conversation', 'user', 'user_permission', 'report_manager', 'report_manager_two',
            'report_day', 'report_deviation', 'report_delivery_category', 'report_delivery', 'report_transport', 'report_transporter',
            'report_counterparty', 'report_summary', 'report_summary_remains', 'debtor', 'option', 'option_edit'])->delete();
    }
};
