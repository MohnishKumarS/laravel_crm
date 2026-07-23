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