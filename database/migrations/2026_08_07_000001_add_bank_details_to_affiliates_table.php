<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->string('bank_account_holder')->nullable()->after('paypal_email');
            $table->text('bank_account_number')->nullable()->after('bank_account_holder'); // encrypted at rest, hence text not string
            $table->string('bank_name')->nullable()->after('bank_account_number');
            $table->string('bank_ifsc')->nullable()->after('bank_name'); // IFSC (India) - rename to bank_routing_number if targeting other regions
        });
    }

    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn(['bank_account_holder', 'bank_account_number', 'bank_name', 'bank_ifsc']);
        });
    }
};
