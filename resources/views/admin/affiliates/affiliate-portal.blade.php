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
EOF

cat > commissions.blade.php << 'EOF'
@extends('admin.layouts.main')

@section('title', 'My Commissions')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">My Commissions</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr><th>Order Ref</th><th>Order Date</th><th>Total</th><th>Commission</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($commissions as $commission)
                            <tr>
                                <td>{{ $commission->order->reference_no ?? ('#' . $commission->order_id) }}</td>
                                <td>{{ $commission->order?->date ?? '—' }}</td>
                                <td>{{ number_format($commission->order_total, 2) }}</td>
                                <td>{{ number_format($commission->commission_amount, 2) }}</td>
                                <td>
                                    <span class="badge badge-{{ ['pending' => 'warning', 'approved' => 'info', 'paid' => 'success', 'reversed' => 'danger'][$commission->status] }}">
                                        {{ ucfirst($commission->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted">No commissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $commissions->links() }}
        </div>
    </div>
@endsection
EOF

cat > payouts.blade.php << 'EOF'
@extends('admin.layouts.main')

@section('title', 'My Payouts')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">My Payouts</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr><th>#</th><th>Amount</th><th>Status</th><th>Reference</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($payouts as $payout)
                            <tr>
                                <td>{{ $payout->id }}</td>
                                <td>{{ number_format($payout->amount, 2) }}</td>
                                <td>{{ $payout->paid_at ? 'Paid ' . $payout->paid_at->format('Y-m-d') : 'Awaiting payment' }}</td>
                                <td>{{ $payout->reference ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-muted">No payouts yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $payouts->links() }}
        </div>
    </div>
@endsection