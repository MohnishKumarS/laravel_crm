<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\Affiliate;
use App\Models\Affiliate\TrainingLesson;
use App\Models\Affiliate\TrainingQuestion;
use Illuminate\Http\Request;

class TrainingProgressController extends Controller
{
    /**
     * Overview: every affiliate with a quick summary of where they
     * stand - lessons watched out of total, latest quiz result, and
     * whether training is fully complete.
     */
    public function index(Request $request)
    {
        $totalLessons = TrainingLesson::where('is_active', true)->count();

        $affiliates = Affiliate::with(['lessonProgress', 'quizAttempts' => fn ($q) => $q->latest('attempted_at')])
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'completed') {
                    $q->whereNotNull('training_completed_at');
                } elseif ($request->status === 'not_completed') {
                    $q->whereNull('training_completed_at');
                }
            })
            ->paginate(20)
            ->withQueryString();

        return view('admin.affiliates.training-progress.index', compact('affiliates', 'totalLessons'));
    }

    /**
     * Detail: one affiliate's full lesson-by-lesson watch status and
     * every quiz attempt with a question-by-question answer breakdown.
     */
    public function show(Affiliate $affiliate)
    {
        $affiliate->load([
            'lessonProgress.lesson',
            'quizAttempts' => fn ($q) => $q->latest('attempted_at'),
        ]);

        $allLessons = TrainingLesson::where('is_active', true)->orderBy('sort_order')->get();
        $allQuestions = TrainingQuestion::where('is_active', true)->get()->keyBy('id');

        return view('admin.affiliates.training-progress.show', compact('affiliate', 'allLessons', 'allQuestions'));
    }
}
