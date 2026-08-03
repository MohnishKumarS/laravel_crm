@extends('admin.layouts.main')

@section('title', 'Affiliate Social Submissions | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Social Post Submissions</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.index') }}">Affiliate Program</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Social Submissions</a></li>
        </ul>
    </div>

    @if (session('success'))
        <h5 class="alert alert-success">{{ session('success') }}</h5>
    @endif

    <div class="card">
        <div class="card-header"><div class="card-title">Review Queue</div></div>
        <div class="card-body">
            <form method="GET" class="d-flex align-items-center gap-2 mb-3">
                <select name="status" class="form-control" style="max-width: 180px" onchange="this.form.submit()">
                    <option value="">All statuses</option>
                    @foreach (['pending', 'approved', 'rejected'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Affiliate</th>
                            <th>Platform</th>
                            <th>Post Link</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $submission)
                            <tr>
                                <td>{{ $submission->affiliate->affiliate_code }}</td>
                                <td>{{ ucfirst($submission->platform) }}</td>
                                <td>
                                    <a href="{{ $submission->post_url }}" target="_blank" rel="noopener" class="text-truncate d-inline-block" style="max-width: 250px;">
                                        {{ $submission->post_url }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge badge-{{ ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$submission->status] }}">
                                        {{ ucfirst($submission->status) }}
                                    </span>
                                    @if ($submission->admin_note)
                                        <br><small class="text-muted">{{ $submission->admin_note }}</small>
                                    @endif
                                </td>
                                <td>{{ $submission->created_at->format('Y-m-d') }}</td>
                                <td>
                                    @if ($submission->status === 'pending')
                                        <form method="POST" action="{{ route('affiliates.social-submissions.review', $submission) }}" class="d-flex gap-1">
                                            @csrf @method('PUT')
                                            <input type="text" name="admin_note" class="form-control form-control-sm" placeholder="Note (optional)" style="max-width: 150px">
                                            <button type="submit" name="status" value="approved" class="btn btn-sm btn-success">Approve</button>
                                            <button type="submit" name="status" value="rejected" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    @else
                                        <span class="text-muted">
                                            Reviewed {{ $submission->reviewed_at?->format('Y-m-d') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $submissions->links() }}
        </div>
    </div>
@endsection
