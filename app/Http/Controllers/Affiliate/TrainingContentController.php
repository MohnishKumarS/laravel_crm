<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\Affiliate\TrainingLesson;
use App\Models\Affiliate\TrainingQuestion;
use Illuminate\Http\Request;

/**
 * Minimal admin CRUD for training content. No edit forms included to
 * keep this shippable in-scope - admins can add/deactivate/delete, but
 * to change wording, delete and re-add. Add edit() if that's needed.
 */
class TrainingContentController extends Controller
{
    public function index()
    {
        $lessons = TrainingLesson::orderBy('sort_order')->get();
        $questions = TrainingQuestion::latest()->get();

        return view('admin.affiliates.training', compact('lessons', 'questions'));
    }

    public function storeLesson(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'video_url'   => ['required', 'url'],
            'description' => ['nullable', 'string'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        TrainingLesson::create($request->only('title', 'video_url', 'description', 'sort_order'));

        return back()->with('success', 'Lesson added.');
    }

    public function toggleLesson(TrainingLesson $lesson)
    {
        $lesson->update(['is_active' => !$lesson->is_active]);

        return back()->with('success', 'Lesson visibility updated.');
    }

    public function destroyLesson(TrainingLesson $lesson)
    {
        $lesson->delete();

        return back()->with('success', 'Lesson deleted.');
    }

    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question'       => ['required', 'string'],
            'option_a'       => ['required', 'string', 'max:255'],
            'option_b'       => ['required', 'string', 'max:255'],
            'option_c'       => ['nullable', 'string', 'max:255'],
            'option_d'       => ['nullable', 'string', 'max:255'],
            'correct_option' => ['required', 'in:a,b,c,d'],
        ]);

        TrainingQuestion::create($request->only(
            'question', 'option_a', 'option_b', 'option_c', 'option_d', 'correct_option'
        ));

        return back()->with('success', 'Question added.');
    }

    public function toggleQuestion(TrainingQuestion $question)
    {
        $question->update(['is_active' => !$question->is_active]);

        return back()->with('success', 'Question visibility updated.');
    }

    public function destroyQuestion(TrainingQuestion $question)
    {
        $question->delete();

        return back()->with('success', 'Question deleted.');
    }
}
