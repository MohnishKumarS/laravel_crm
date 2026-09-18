@extends('admin.layouts.main')

@section('title', 'My Partner Dashboard | Yuukke')

@section('content')
<style>
    .dash-hero {
        background: linear-gradient(135deg, #4e73df 0%, #6f42c1 100%);
        border-radius: 12px;
        padding: 26px 24px;
        color: #fff;
        margin-bottom: 24px;
    }
    .dash-stat-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        height: 100%;
    }
    .dash-stat-card .card-body { padding: 20px; }
    .dash-stat-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        margin-bottom: 10px;
    }
    .dash-stat-value { font-size: 24px; font-weight: 700; margin: 0; }
    .dash-stat-label { font-size: 13px; color: #8a92a3; margin-bottom: 6px; }
    .referral-card {
        border: none;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .referral-link-box {
        background: #f6f7fa;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 13px;
        word-break: break-all;
        color: #495057;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .referral-link-box code { background: none; color: inherit; padding: 0; }
    .btn-copy-link {
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
        flex-shrink: 0;
    }
    .code-chip {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        padding: 4px 12px;
        border-radius: 20px;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-top: 6px;
    }

    @media (max-width: 575px) {
        .dash-hero { padding: 20px 16px; }
        .dash-stat-value { font-size: 20px; }
    }
</style>

<div class="dash-hero">
    <h4 class="fw-bold mb-1">Welcome back, {{ $affiliate->user->name ?? 'Partner' }} 👋</h4>
    <p class="mb-0" style="opacity:0.9;">Here's how your promotions are performing.</p>
</div>

<div class="row g-3">
    <div class="col-6 col-md-4">
        <div class="card dash-stat-card">
            <div class="card-body">
                <div class="dash-stat-icon" style="background:#fff4e5;color:#f5a623;">⏳</div>
                <p class="dash-stat-label">Pending</p>
                <p class="dash-stat-value">{{ number_format($affiliate->pendingBalance(), 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card dash-stat-card">
            <div class="card-body">
                <div class="dash-stat-icon" style="background:#e8f0fe;color:#4e73df;">💰</div>
                <p class="dash-stat-label">Approved (unpaid)</p>
                <p class="dash-stat-value">{{ number_format($affiliate->unpaidBalance(), 2) }}</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card dash-stat-card">
            <div class="card-body">
                <div class="dash-stat-icon" style="background:#e6f7ee;color:#28a745;">✓</div>
                <p class="dash-stat-label">Lifetime Paid</p>
                <p class="dash-stat-value">{{ number_format($affiliate->lifetime_paid, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card referral-card">
            <div class="card-header bg-white" style="border-bottom:1px solid #f0f1f4;">
                <div class="card-title mb-0 fw-bold">Your Referral Link</div>
            </div>
            <div class="card-body">
                <p class="mb-1 text-muted small">Referral Code</p>
                <span class="code-chip" style="background:#f6f7fa;color:#4e73df;">{{ $affiliate->affiliate_code }}</span>

                <p class="mb-1 mt-3 text-muted small">Shareable Link</p>
                <div class="referral-link-box" id="referral-link">
                    <code>{{ $affiliate->referralUrl() }}</code>
                    <button type="button" class="btn btn-sm btn-primary btn-copy-link"
                        onclick="navigator.clipboard.writeText('{{ $affiliate->referralUrl() }}'); this.innerText='Copied!'; setTimeout(() => this.innerText='Copy Link', 1500)">
                        Copy Link
                    </button>
                </div>

                <div class="mt-3">
                    @if ($affiliate->status === 'approved')
                        <span class="badge badge-success">✓ Active — your link is tracking clicks</span>
                    @else
                        <span class="badge badge-warning">{{ ucfirst($affiliate->status) }}</span>
                        <p class="text-muted small mt-1 mb-0">Your link won't track clicks until your account is approved.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection