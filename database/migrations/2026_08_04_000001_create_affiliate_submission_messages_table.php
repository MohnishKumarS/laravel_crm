<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_submission_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('affiliate_social_submissions')->cascadeOnDelete();
            $table->foreignId('sender_user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('sender_role', ['affiliate', 'admin']);
            $table->text('message');
            $table->timestamp('read_at')->nullable(); // read by the OTHER party
            $table->timestamps();

            $table->index(['submission_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_submission_messages');
    }
};
