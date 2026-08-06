@extends('admin.layouts.main')

@section('title', 'Training Progress | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Training Progress</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.index') }}">Partner Program</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Training Progress</a></li>
        </ul>
    </div>

    <div class="card">
        <div class="card-header"><div class="card-title">All Partner — Training Overview</div></div>
        <div class="card-body">
            <form method="GET" class="d-flex align-items-center gap-2 mb-3">
                <select name="status" class="form-control" style="max-width: 200px" onchange="this.form.submit()">
                    <option value="">All partner</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Training completed</option>
                    <option value="not_completed" {{ request('status') === 'not_completed' ? 'selected' : '' }}>Not completed</option>
                </select>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Partner</th>
                            <th>Lessons Watched</th>
                            <th>Latest Quiz Score</th>
                            <th>Training Status</th>
                            <th>KYC Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($affiliates as $affiliate)
                            @php $latestAttempt = $affiliate->quizAttempts->first(); @endphp
                            <tr>
                                <td>{{ $affiliate->affiliate_code }} — {{ $affiliate->user->name ?? '—' }}</td>
                                <td>{{ $affiliate->lessonProgress->count() }} / {{ $totalLessons }}</td>
                                <td>
                                    @if ($latestAttempt)
                                        {{ $latestAttempt->score }}/{{ $latestAttempt->total_questions }}
                                        <span class="badge badge-{{ $latestAttempt->passed ? 'success' : 'danger' }}">
                                            {{ $latestAttempt->passed ? 'Passed' : 'Failed' }}
                                        </span>
                                    @else
                                        <span class="text-muted">No attempts</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($affiliate->isTrainingCompleted())
                                        <span class="badge badge-success">✓ Completed</span>
                                    @else
                                        <span class="badge badge-secondary">Not completed</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ ['not_submitted' => 'secondary', 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$affiliate->kyc_status] }}">
                                        {{ ucfirst(str_replace('_', ' ', $affiliate->kyc_status)) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('affiliates.training-progress.show', $affiliate) }}" class="btn btn-sm btn-outline-primary">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $affiliates->links() }}
        </div>
    </div>
@endsection
