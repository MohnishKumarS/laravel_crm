@extends('admin.layouts.main')

@section('title', 'Social Post Submissions')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Social Post Submissions</h3>
    </div>

    @if (session('success'))
        <h5 class="alert alert-success">{{ session('success') }}</h5>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><div class="card-title">Submit a New Post</div></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('affiliate.social-submissions.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Post URL</label>
                            <input type="url" name="post_url" class="form-control" placeholder="https://instagram.com/p/..." value="{{ old('post_url') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Platform</label>
                            <select name="platform" class="form-control" required>
                                <option value="instagram">Instagram</option>
                                <option value="facebook">Facebook</option>
                                <option value="youtube">YouTube</option>
                                <option value="tiktok">TikTok</option>
                                <option value="twitter">Twitter / X</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        @if ($products->isNotEmpty())
                            <div class="form-group">
                                <label>Which product? (optional)</label>
                                <select name="product_id" class="form-control">
                                    <option value="">— None specific —</option>
                                    @foreach ($products as $p)
                                        <option value="{{ $p->product_id }}">{{ $p->product_name ?? ('Product #' . $p->product_id) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <button class="btn btn-success mt-2">Submit for Review</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><div class="card-title">My Submission History</div></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr><th>Platform</th><th>Link</th><th>Status</th><th>Note</th><th>Submitted</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($submissions as $submission)
                                    <tr>
                                        <td>{{ ucfirst($submission->platform) }}</td>
                                        <td><a href="{{ $submission->post_url }}" target="_blank" rel="noopener">View</a></td>
                                        <td>
                                            <span class="badge badge-{{ ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$submission->status] }}">
                                                {{ ucfirst($submission->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $submission->admin_note ?? '—' }}</td>
                                        <td>{{ $submission->created_at->format('Y-m-d') }}</td>
                                         <td>
        <button class="btn btn-sm btn-outline-secondary"
            onclick="document.getElementById('thread-{{ $submission->id }}').classList.toggle('d-none')">
            Messages
        </button>
    </td>
                                    </tr>
                                    <tr id="thread-{{ $submission->id }}" class="d-none">
    <td colspan="6">
        @include('admin.affiliates._message_thread', [
            'submission' => $submission,
            'sendRoute'  => route('affiliate.social-submissions.message', $submission),
        ])
    </td>
</tr>
                                @empty
                                    <tr><td colspan="5" class="text-muted">No submissions yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $submissions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
