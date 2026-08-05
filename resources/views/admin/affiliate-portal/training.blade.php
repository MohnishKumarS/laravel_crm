@extends('admin.layouts.main')

@section('title', 'Training Videos')

@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="fw-bold mb-3">Training</h3>
        <a href="{{ route('affiliate.quiz') }}" class="btn btn-primary btn-sm">Take the Quiz →</a>
    </div>

    @if (session('success'))<h5 class="alert alert-success">{{ session('success') }}</h5>@endif

    @forelse ($lessons as $lesson)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <h5>{{ $lesson->title }}</h5>
                    @if (in_array($lesson->id, $watchedLessonIds))
                        <span class="badge badge-success">✓ Watched</span>
                    @endif
                </div>
                @if ($lesson->description)<p class="text-muted">{{ $lesson->description }}</p>@endif
                <div class="ratio ratio-16x9 mb-2">
                    <iframe src="{{ $lesson->video_url }}" allowfullscreen></iframe>
                </div>
                @unless (in_array($lesson->id, $watchedLessonIds))
                    <form method="POST" action="{{ route('affiliate.training.watched', $lesson->id) }}">
                        @csrf
                        <button class="btn btn-sm btn-outline-success">Mark as Watched</button>
                    </form>
                @endunless
            </div>
        </div>
    @empty
        <p class="text-muted">No training videos available yet.</p>
    @endforelse
@endsection
