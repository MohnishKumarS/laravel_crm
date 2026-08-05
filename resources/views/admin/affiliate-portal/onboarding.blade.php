@extends('admin.layouts.main')

@section('title', 'Affiliate Onboarding')

@section('content')
    <div class="page-header"><h3 class="fw-bold mb-3">Onboarding</h3></div>

    @if (session('success'))<h5 class="alert alert-success">{{ session('success') }}</h5>@endif
    @if (session('error'))<h5 class="alert alert-danger">{{ session('error') }}</h5>@endif
    @if (session('message'))<h5 class="alert alert-{{ session('status', 'info') }}">{{ session('message') }}</h5>@endif
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
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>1. Account Approval</h5>
                    <span class="badge badge-{{ $affiliate->status === 'approved' ? 'success' : 'warning' }}">
                        {{ ucfirst($affiliate->status) }}
                    </span>
                    <p class="text-muted mt-2 mb-0">Handled by admin after you sign up.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>2. KYC Verification</h5>
                    <span class="badge badge-{{ ['not_submitted' => 'secondary', 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$affiliate->kyc_status] }}">
                        {{ ucfirst(str_replace('_', ' ', $affiliate->kyc_status)) }}
                    </span>

                    @if (in_array($affiliate->kyc_status, ['not_submitted', 'rejected']))
                        <form method="POST" action="{{ route('affiliate.kyc.upload') }}" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <select name="document_type" class="form-control form-control-sm mb-2" required>
                                <option value="id_proof">ID Proof</option>
                                <option value="address_proof">Address Proof</option>
                                <option value="other">Other</option>
                            </select>
                            <input type="file" name="file" class="form-control form-control-sm mb-2" accept=".jpg,.jpeg,.png,.pdf" required>
                            <button class="btn btn-sm btn-primary w-100">Upload Document</button>
                        </form>
                    @elseif ($affiliate->kyc_status === 'pending')
                        <p class="text-muted mt-2 mb-0">Under review — check back soon.</p>
                    @endif

                    @if ($kycDocuments->isNotEmpty())
                        <hr>
                        <small class="text-muted d-block mb-1">Submitted documents:</small>
                        @foreach ($kycDocuments as $doc)
                            <div class="small">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }} —
                                <span class="badge badge-{{ $doc->status === 'approved' ? 'success' : ($doc->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($doc->status) }}</span>
                                @if ($doc->admin_note)<br><em class="text-muted">{{ $doc->admin_note }}</em>@endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>3. Training & Quiz</h5>
                    @if ($affiliate->isTrainingCompleted())
                        <span class="badge badge-success">Completed</span>
                    @else
                        <span class="badge badge-secondary">Not completed</span>
                        <div class="mt-3 d-flex flex-column gap-2">
                            <a href="{{ route('affiliate.training') }}" class="btn btn-sm btn-outline-primary">Watch Training Videos</a>
                            <a href="{{ route('affiliate.quiz') }}" class="btn btn-sm btn-primary">Take the Quiz</a>
                        </div>
                    @endif

                    @if ($latestAttempt)
                        <p class="text-muted mt-2 mb-0 small">
                            Last attempt: {{ $latestAttempt->score }}/{{ $latestAttempt->total_questions }}
                            ({{ $latestAttempt->passed ? 'Passed' : 'Did not pass' }})
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if ($affiliate->isFullyOnboarded())
        <div class="alert alert-success mt-3">
            🎉 You're fully onboarded! <a href="{{ route('affiliate.products') }}">Start selecting products to promote</a>.
        </div>
    @endif
@endsection
