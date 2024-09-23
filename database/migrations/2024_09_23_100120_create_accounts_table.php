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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean("is_default")->default(0);
            "isDefault": true,
            "accountNumber": "40702810241650100486",
            "bankName": "РНКБ БАНК (ПАО)",
            "bankLocation": "Г СИМФЕРОПОЛЬ",
            "correspondentAccount": "30101810335100000607",
            "bic": "043510607"
            $table->integer("account_number");
            $table->string("bank_name");
            
            $table->char('ms_id', 36)->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
