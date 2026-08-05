@extends('admin.layouts.main')

@section('title', 'Training Progress | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Training Progress — {{ $affiliate->affiliate_code }}</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.index') }}">Affiliate Program</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.training-progress.index') }}">Training Progress</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">{{ $affiliate->affiliate_code }}</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><div class="card-title">Overview</div></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $affiliate->user->name ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $affiliate->user->email ?? '—' }}</p>
                    <p>
                        <strong>Training:</strong>
                        @if ($affiliate->isTrainingCompleted())
                            <span class="badge badge-success">✓ Completed {{ $affiliate->training_completed_at->format('Y-m-d') }}</span>
                        @else
                            <span class="badge badge-secondary">Not completed</span>
                        @endif
                    </p>
                    <p>
                        <strong>KYC:</strong>
                        <span class="badge badge-{{ ['not_submitted' => 'secondary', 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$affiliate->kyc_status] }}">
                            {{ ucfirst(str_replace('_', ' ', $affiliate->kyc_status)) }}
                        </span>
                    </p>
                    <a href="{{ route('affiliates.show', $affiliate) }}" class="btn btn-sm btn-outline-secondary">View Full Affiliate Profile</a>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><div class="card-title">Lessons Watched</div></div>
                <div class="card-body">
                    <table class="table table-sm">
                        <thead><tr><th>Lesson</th><th>Status</th><th>Watched On</th></tr></thead>
                        <tbody>
                            @foreach ($allLessons as $lesson)
                                @php $progress = $affiliate->lessonProgress->firstWhere('lesson_id', $lesson->id); @endphp
                                <tr>
                                    <td>{{ $lesson->title }}</td>
                                    <td>{{ $progress ? '✅ Watched' : '⬜ Not watched' }}</td>
                                    <td>{{ $progress?->watched_at->format('Y-m-d H:i') ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><div class="card-title">Quiz Attempts</div></div>
                <div class="card-body">
                    @forelse ($affiliate->quizAttempts as $attempt)
                        <div class="border rounded p-3 mb-3">
                            <p class="mb-2">
                                <strong>{{ $attempt->score }}/{{ $attempt->total_questions }}</strong>
                                <span class="badge badge-{{ $attempt->passed ? 'success' : 'danger' }}">
                                    {{ $attempt->passed ? 'Passed' : 'Failed' }}
                                </span>
                                <small class="text-muted ms-2">{{ $attempt->attempted_at->format('Y-m-d H:i') }}</small>
                            </p>

                            @if ($attempt->answers)
                                <table class="table table-sm mb-0">
                                    <thead><tr><th>Question</th><th>Answered</th><th>Correct Answer</th><th></th></tr></thead>
                                    <tbody>
                                        @foreach ($attempt->answers as $questionId => $selected)
                                            @php $q = $allQuestions->get($questionId); @endphp
                                            @if ($q)
                                                <tr class="{{ $selected === $q->correct_option ? '' : 'table-danger' }}">
                                                    <td>{{ $q->question }}</td>
                                                    <td>{{ $q->{"option_$selected"} ?? $selected }}</td>
                                                    <td>{{ $q->{"option_{$q->correct_option}"} }}</td>
                                                    <td>{{ $selected === $q->correct_option ? '✅' : '❌' }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">No quiz attempts yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
