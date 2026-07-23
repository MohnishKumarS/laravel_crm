
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