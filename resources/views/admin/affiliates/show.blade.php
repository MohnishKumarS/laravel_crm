@extends('admin.layouts.main')

@section('title', 'Affiliate Detail | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">{{ $affiliate->affiliate_code }}</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.index') }}">Affiliate Program</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">{{ $affiliate->affiliate_code }}</a></li>
        </ul>
    </div>

    @if (session('success'))
        <h5 class="alert alert-success">{{ session('success') }}</h5>
    @endif

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><div class="card-title">Overview</div></div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $affiliate->user->name ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $affiliate->user->email ?? '—' }}</p>
                    <p>
                        <strong>Status:</strong>
                        <span class="badge badge-{{ $affiliate->status === 'approved' ? 'success' : ($affiliate->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($affiliate->status) }}
                        </span>
                    </p>
                    <p><strong>Referral URL:</strong><br>
                        <code style="word-break: break-all;">{{ $affiliate->referralUrl() }}</code>
                    </p>
                    <p><strong>Pending:</strong> {{ number_format($affiliate->pendingBalance(), 2) }}</p>
                    <p><strong>Approved (unpaid):</strong> {{ number_format($affiliate->unpaidBalance(), 2) }}</p>
                    <p><strong>Lifetime paid:</strong> {{ number_format($affiliate->lifetime_paid, 2) }}</p>

                    <hr>

                    <form action="{{ route('affiliates.rate', $affiliate) }}" method="POST" class="form-inline">
                        @csrf @method('PUT')
                        <label class="mr-2 mb-0">Commission rate</label>
                        <input type="number" step="0.01" name="commission_rate" class="form-control form-control-sm mr-2"
                            style="width: 80px" value="{{ $affiliate->commission_rate }}">
                        <button class="btn btn-sm btn-outline-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><div class="card-title">Commission History</div></div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr><th>Order</th><th>Order Date</th><th>Total</th><th>Rate</th><th>Commission</th><th>Status</th><th>Created</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($affiliate->commissions as $commission)
                                <tr>
                                    <td>
                                        @if ($commission->order)
                                            {{ $commission->order->reference_no ?? ('#' . $commission->order_id) }}
                                        @else
                                            <span class="text-muted">#{{ $commission->order_id }} (order not found)</span>
                                        @endif
                                    </td>
                                    <td>{{ $commission->order?->date ?? '—' }}</td>
                                    <td>{{ number_format($commission->order_total, 2) }}</td>
                                    <td>{{ $commission->commission_rate }}%</td>
                                    <td>{{ number_format($commission->commission_amount, 2) }}</td>
                                    <td>
                                        <span class="badge badge-{{ ['pending' => 'warning', 'approved' => 'info', 'paid' => 'success', 'reversed' => 'danger'][$commission->status] }}">
                                            {{ ucfirst($commission->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $commission->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-muted">No commissions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><div class="card-title">Payout History</div></div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr><th>#</th><th>Amount</th><th>Status</th><th>Reference</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($affiliate->payouts as $payout)
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
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><div class="card-title">Recent Clicks</div></div>
                <div class="card-body">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr><th>Source</th><th>Landing URL</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($affiliate->clicks as $click)
                                <tr>
                                    <td>{{ ucfirst($click->source_type) }}</td>
                                    <td class="text-truncate" style="max-width: 300px;">{{ $click->landing_url ?? '—' }}</td>
                                    <td>{{ $click->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted">No clicks recorded yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection