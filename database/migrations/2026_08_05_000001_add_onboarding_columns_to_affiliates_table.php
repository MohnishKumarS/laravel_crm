<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->enum('kyc_status', ['not_submitted', 'pending', 'approved', 'rejected'])
                ->default('not_submitted')->after('status');
            $table->timestamp('kyc_submitted_at')->nullable()->after('kyc_status');
            $table->timestamp('kyc_reviewed_at')->nullable()->after('kyc_submitted_at');
            $table->timestamp('training_completed_at')->nullable()->after('kyc_reviewed_at');
        });
    }

    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn(['kyc_status', 'kyc_submitted_at', 'kyc_reviewed_at', 'training_completed_at']);
        });
    }
};
