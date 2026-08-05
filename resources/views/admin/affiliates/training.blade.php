@extends('admin.layouts.main')

@section('title', 'Training Content')

@section('content')
    <div class="page-header"><h3 class="fw-bold mb-3">Training Content</h3></div>

    @if (session('success'))<h5 class="alert alert-success">{{ session('success') }}</h5>@endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><div class="card-title">Lessons</div></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('affiliates.training.lessons.store') }}" class="mb-3 border-bottom pb-3">
                        @csrf
                        <input type="text" name="title" class="form-control form-control-sm mb-1" placeholder="Title" required>
                        <input type="url" name="video_url" class="form-control form-control-sm mb-1" placeholder="Video embed URL" required>
                        <input type="number" name="sort_order" class="form-control form-control-sm mb-1" placeholder="Sort order (0, 1, 2...)">
                        <button class="btn btn-sm btn-primary">Add Lesson</button>
                    </form>

                    @foreach ($lessons as $lesson)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-1">
                            <span>{{ $lesson->title }} {{ !$lesson->is_active ? '(hidden)' : '' }}</span>
                            <div class="d-flex gap-1">
                                <form method="POST" action="{{ route('affiliates.training.lessons.toggle', $lesson) }}">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-outline-secondary">{{ $lesson->is_active ? 'Hide' : 'Show' }}</button>
                                </form>
                                <form method="POST" action="{{ route('affiliates.training.lessons.destroy', $lesson) }}" onsubmit="return confirm('Delete this lesson?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><div class="card-title">Quiz Questions</div></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('affiliates.training.questions.store') }}" class="mb-3 border-bottom pb-3">
                        @csrf
                        <input type="text" name="question" class="form-control form-control-sm mb-1" placeholder="Question" required>
                        <input type="text" name="option_a" class="form-control form-control-sm mb-1" placeholder="Option A" required>
                        <input type="text" name="option_b" class="form-control form-control-sm mb-1" placeholder="Option B" required>
                        <input type="text" name="option_c" class="form-control form-control-sm mb-1" placeholder="Option C (optional)">
                        <input type="text" name="option_d" class="form-control form-control-sm mb-1" placeholder="Option D (optional)">
                        <select name="correct_option" class="form-control form-control-sm mb-1" required>
                            <option value="a">Correct: A</option>
                            <option value="b">Correct: B</option>
                            <option value="c">Correct: C</option>
                            <option value="d">Correct: D</option>
                        </select>
                        <button class="btn btn-sm btn-primary">Add Question</button>
                    </form>

                    @foreach ($questions as $q)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-1">
                            <span class="text-truncate" style="max-width: 250px;">{{ $q->question }} {{ !$q->is_active ? '(hidden)' : '' }}</span>
                            <div class="d-flex gap-1">
                                <form method="POST" action="{{ route('affiliates.training.questions.toggle', $q) }}">
                                    @csrf @method('PUT')
                                    <button class="btn btn-sm btn-outline-secondary">{{ $q->is_active ? 'Hide' : 'Show' }}</button>
                                </form>
                                <form method="POST" action="{{ route('affiliates.training.questions.destroy', $q) }}" onsubmit="return confirm('Delete this question?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
