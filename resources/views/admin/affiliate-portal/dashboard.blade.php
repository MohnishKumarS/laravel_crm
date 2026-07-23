
@extends('admin.layouts.main')

@section('title', 'My Affiliate Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">My Affiliate Dashboard</h3>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-1">Pending</p>
                    <h4>{{ number_format($affiliate->pendingBalance(), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-1">Approved (unpaid)</p>
                    <h4>{{ number_format($affiliate->unpaidBalance(), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="text-muted mb-1">Lifetime Paid</p>
                    <h4>{{ number_format($affiliate->lifetime_paid, 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><div class="card-title">Your Referral Link</div></div>
                <div class="card-body">
                    <p><strong>Code:</strong> {{ $affiliate->affiliate_code }}</p>
                    <p><strong>Link:</strong><br>
                        <code style="word-break: break-all;">{{ $affiliate->referralUrl() }}</code>
                    </p>
                    <p><strong>Status:</strong>
                        <span class="badge badge-{{ $affiliate->status === 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($affiliate->status) }}
                        </span>
                        @if ($affiliate->status !== 'approved')
                            <br><small class="text-muted">Your link won't track clicks until your account is approved.</small>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

