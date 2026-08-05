<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliate_quiz_attempts', function (Blueprint $table) {
            // stores { "question_id": "selected_option_letter", ... } so
            // admin can review exactly what was answered, not just the score
            $table->json('answers')->nullable()->after('total_questions');
        });

        Schema::create('affiliate_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lesson_id')->constrained('training_lessons')->cascadeOnDelete();
            $table->timestamp('watched_at');
            $table->timestamps();

            $table->unique(['affiliate_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::table('affiliate_quiz_attempts', function (Blueprint $table) {
            $table->dropColumn('answers');
        });
        Schema::dropIfExists('affiliate_lesson_progress');
    }
};
