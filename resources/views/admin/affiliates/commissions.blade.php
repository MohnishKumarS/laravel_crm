@extends('admin.layouts.main')

@section('title', 'Affiliate Commissions | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Affiliate Commissions</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.index') }}">Affiliate Program</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Commissions</a></li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><div class="card-title">Commission Ledger</div></div>
                <div class="card-body">
                    @if (session('success'))
                        <h5 class="alert alert-success">{{ session('success') }}</h5>
                    @endif

                    <form method="POST" action="{{ route('affiliates.commissions.bulk-approve') }}">
                        @csrf @method('PUT')

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" onclick="document.querySelectorAll('.row-check').forEach(c => c.checked = this.checked)"></th>
                                        <th>Affiliate</th>
                                        <th>Order Ref</th>
                                        <th>Order Date</th>
                                        <th>Customer</th>
                                        <th>Order Total</th>
                                        <th>Rate</th>
                                        <th>Commission</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($commissions as $commission)
                                        <tr>
                                            <td>
                                                @if ($commission->status === 'pending')
                                                    <input type="checkbox" class="row-check" name="ids[]" value="{{ $commission->id }}">
                                                @endif
                                            </td>
                                            <td>{{ $commission->affiliate->affiliate_code }}</td>
                                            <td>
                                                @if ($commission->order)
                                                    {{ $commission->order->reference_no ?? ('#' . $commission->order_id) }}
                                                @else
                                                    <span class="text-muted">#{{ $commission->order_id }} (not found)</span>
                                                @endif
                                            </td>
                                            <td>{{ $commission->order?->date ?? '—' }}</td>
                                            <td>{{ $commission->order?->customer ?? '—' }}</td>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-success btn-sm">Approve Selected</button>
                    </form>

                    {{ $commissions->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection