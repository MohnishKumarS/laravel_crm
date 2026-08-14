@extends('admin.layouts.main')

@section('title', 'Affiliate Onboarding')

@section('content')
<style>
    .onboard-hero {
        background: linear-gradient(135deg, #4e73df 0%, #6f42c1 100%);
        border-radius: 12px;
        padding: 28px 24px;
        color: #fff;
        margin-bottom: 24px;
    }
    .onboard-steps {
        display: flex;
        align-items: center;
        margin-top: 20px;
        overflow-x: auto;
    }
    .onboard-step {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    .onboard-step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        background: rgba(255,255,255,0.25);
        color: #fff;
        flex-shrink: 0;
    }
    .onboard-step.done .onboard-step-circle {
        background: #fff;
        color: #28a745;
    }
    .onboard-step-label {
        margin-left: 8px;
        margin-right: 16px;
        font-size: 14px;
        white-space: nowrap;
    }
    .onboard-connector {
        width: 32px;
        height: 2px;
        background: rgba(255,255,255,0.35);
        margin-right: 16px;
        flex-shrink: 0;
    }
    .onboard-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        height: 100%;
    }
    .onboard-card .card-body { padding: 22px; }
    .onboard-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 12px;
    }
    .onboard-form input.form-control,
    .onboard-form select.form-control {
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
    }
    .onboard-form .btn { border-radius: 8px; padding: 10px; font-weight: 600; }
    @media (max-width: 767px) {
        .onboard-hero { padding: 20px 16px; }
        .onboard-step-label { display: none; }
    }
</style>

@php
    $steps = [
        'account' => $affiliate->status === 'approved',
        'kyc' => $affiliate->kyc_status === 'approved',
        'training' => $affiliate->isTrainingCompleted(),
    ];
@endphp

<div class="onboard-hero">
    <h4 class="fw-bold mb-1">Welcome, {{ $affiliate->user->name ?? 'Affiliate' }} 👋</h4>
    <p class="mb-0" style="opacity:0.9;">Complete these steps to start promoting products and earning commissions.</p>

    <div class="onboard-steps">
        <div class="onboard-step {{ $steps['account'] ? 'done' : '' }}">
            <div class="onboard-step-circle">{{ $steps['account'] ? '✓' : '1' }}</div>
            <span class="onboard-step-label">Account</span>
        </div>
        <div class="onboard-connector"></div>
        <div class="onboard-step {{ $steps['kyc'] ? 'done' : '' }}">
            <div class="onboard-step-circle">{{ $steps['kyc'] ? '✓' : '2' }}</div>
            <span class="onboard-step-label">KYC</span>
        </div>
        <div class="onboard-connector"></div>
        <div class="onboard-step {{ $steps['training'] ? 'done' : '' }}">
            <div class="onboard-step-circle">{{ $steps['training'] ? '✓' : '3' }}</div>
            <span class="onboard-step-label">Training</span>
        </div>
    </div>
</div>

@if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
@if (session('message'))<div class="alert alert-{{ session('status', 'info') }}">{{ session('message') }}</div>@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="row g-3">
    {{-- STEP 1: ACCOUNT --}}
    <div class="col-12 col-md-4">
        <div class="card onboard-card">
            <div class="card-body">
                <div class="onboard-icon" style="background:#e8f0fe;color:#4e73df;">1</div>
                <h6 class="fw-bold">Account Approval</h6>
                <span class="badge rounded-pill {{ $affiliate->status === 'approved' ? 'badge-success' : 'badge-warning' }} mb-2">
                    {{ ucfirst($affiliate->status) }}
                </span>
                <p class="text-muted small mb-0">Reviewed by our team after you sign up. No action needed from you here.</p>
            </div>
        </div>
    </div>

    {{-- STEP 2: KYC --}}
    <div class="col-12 col-md-4">
        <div class="card onboard-card">
            <div class="card-body">
                <div class="onboard-icon" style="background:#fff4e5;color:#f5a623;">2</div>
                <h6 class="fw-bold">KYC Verification</h6>
                <span class="badge rounded-pill mb-2 badge-{{ ['not_submitted' => 'secondary', 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'][$affiliate->kyc_status] }}">
                    {{ ucfirst(str_replace('_', ' ', $affiliate->kyc_status)) }}
                </span>

                @if (in_array($affiliate->kyc_status, ['not_submitted', 'rejected']))
                    <form method="POST" action="{{ route('affiliate.kyc.upload') }}" enctype="multipart/form-data" class="onboard-form mt-2">
                        @csrf
                        <div class="mb-2">
                            <select name="document_type" class="form-control form-control-sm" required>
                                <option value="id_proof">ID Proof</option>
                                <option value="address_proof">Address Proof</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="file" name="file" class="form-control form-control-sm" accept=".jpg,.jpeg,.png,.pdf" required>
                            <small class="text-muted">JPG, PNG, or PDF — max 5MB</small>
                        </div>
                        <button class="btn btn-sm btn-primary w-100">Upload Document</button>
                    </form>
                @elseif ($affiliate->kyc_status === 'pending')
                    <p class="text-muted small mb-0">⏳ Under review — check back soon.</p>
                @else
                    <p class="text-success small mb-0">✓ Verified</p>
                @endif

                @if ($kycDocuments->isNotEmpty())
                    <div class="mt-2 pt-2 border-top">
                        @foreach ($kycDocuments as $doc)
                            <div class="small text-muted">
                                {{ ucfirst(str_replace('_', ' ', $doc->document_type)) }} —
                                <span class="badge badge-{{ $doc->status === 'approved' ? 'success' : ($doc->status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($doc->status) }}</span>
                                @if ($doc->admin_note)<br><em>{{ $doc->admin_note }}</em>@endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- STEP 3: TRAINING --}}
    <div class="col-12 col-md-4">
        <div class="card onboard-card">
            <div class="card-body">
                <div class="onboard-icon" style="background:#e6f7ee;color:#28a745;">3</div>
                <h6 class="fw-bold">Training &amp; Quiz</h6>

                @if ($affiliate->isTrainingCompleted())
                    <span class="badge rounded-pill badge-success mb-2">✓ Completed</span>
                @else
                    <span class="badge rounded-pill badge-secondary mb-2">Not completed</span>
                    <div class="d-grid gap-2 mt-2">
                        <a href="{{ route('affiliate.training') }}" class="btn btn-sm btn-outline-primary">📺 Watch Training Videos</a>
                        <a href="{{ route('affiliate.quiz') }}" class="btn btn-sm btn-primary">📝 Take the Quiz</a>
                    </div>
                @endif

                @if ($latestAttempt)
                    <p class="text-muted small mt-2 mb-0">
                        Last attempt: {{ $latestAttempt->score }}/{{ $latestAttempt->total_questions }}
                        ({{ $latestAttempt->passed ? 'Passed' : 'Did not pass' }})
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

@if ($affiliate->isFullyOnboarded())
    <div class="alert alert-success mt-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span>🎉 You're fully onboarded and ready to go!</span>
        <a href="{{ route('affiliate.products') }}" class="btn btn-sm btn-success">Start Selecting Products →</a>
    </div>
@endif
@endsection