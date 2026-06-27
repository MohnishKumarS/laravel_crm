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
        Schema::table('forms', function (Blueprint $table) {
        $table->boolean('send_email')->default(false);
        $table->string('email_field_name')->nullable(); // which field holds the customer's email
        $table->string('customer_subject')->nullable();
        $table->longText('customer_template')->nullable();
        $table->string('admin_email')->nullable();
        $table->string('admin_subject')->nullable();
        $table->longText('admin_template')->nullable();
      });
    }

    /**
     * Reverse the migrations.
     */
    // public function down(): void
    // {
    //     Schema::table('forms', function (Blueprint $table) {
    //         //
    //     });
    // }
};
