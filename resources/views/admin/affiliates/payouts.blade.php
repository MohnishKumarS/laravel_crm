@extends('admin.layouts.main')

@section('title', 'Affiliate Payouts | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Affiliate Payouts</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home"><a href="{{ route('dashboard') }}"><i class="icon-home"></i></a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="{{ route('affiliates.index') }}">Affiliate Program</a></li>
            <li class="separator"><i class="icon-arrow-right"></i></li>
            <li class="nav-item"><a href="#">Payouts</a></li>
        </ul>
    </div>

    @if (session('success'))
        <h5 class="alert alert-success">{{ session('success') }}</h5>
    @endif
    @if (session('error'))
        <h5 class="alert alert-danger">{{ session('error') }}</h5>
    @endif

    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header"><div class="card-title">Ready to Pay Out</div></div>
                <div class="card-body">
                    @forelse ($eligible as $row)
                        <form method="POST" action="{{ route('affiliates.payouts.create-batch') }}" class="d-flex justify-content-between align-items-center border-bottom py-2">
                            @csrf
                            <input type="hidden" name="affiliate_id" value="{{ $row['affiliate']->id }}">
                            <span>{{ $row['affiliate']->affiliate_code }} — {{ number_format($row['balance'], 2) }}</span>
                            <button class="btn btn-sm btn-primary">Create Payout Batch</button>
                        </form>
                    @empty
                        <p class="text-muted">No affiliates currently above the minimum payout threshold.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header"><div class="card-title">Payout History</div></div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table table-striped ">
                        <thead>
                            <tr>
                                <th>#</th><th>Affiliate</th><th>Amount</th><th>Status</th><th>Reference</th><th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payouts as $payout)
                                <tr>
                                    <td>{{ $payout->id }}</td>
                                    <td>{{ $payout->affiliate->affiliate_code }}</td>
                                    <td>{{ number_format($payout->amount, 2) }}</td>
                                    <td>
                                        @if ($payout->paid_at)
                                            <span class="badge badge-success">Paid {{ $payout->paid_at->format('Y-m-d') }}</span>
                                        @else
                                            <span class="badge badge-warning">Awaiting manual payment</span>
                                        @endif
                                    </td>
                                    <td>{{ $payout->reference ?? '—' }}</td>
                                    <td>
                                        @unless ($payout->paid_at)
                                            <button type="button" class="btn btn-sm btn-success"
                                                onclick="document.getElementById('mark-paid-{{ $payout->id }}').style.display='block'">
                                                Mark Paid
                                            </button>
                                            <form id="mark-paid-{{ $payout->id }}" method="POST"
                                                action="{{ route('affiliates.payouts.mark-paid', $payout) }}" style="display:none" class="mt-2">
                                                @csrf @method('PUT')
                                                <select name="method" class="form-control form-control-sm mb-1">
                                                    <option value="paypal">PayPal</option>
                                                    <option value="bank_transfer">Bank Transfer</option>
                                                    <option value="other">Other</option>
                                                </select>
                                                <input type="text" name="reference" class="form-control form-control-sm mb-1" placeholder="Transaction reference">
                                                <button class="btn btn-sm btn-outline-success">Confirm Paid</button>
                                            </form>
                                        @endunless
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    {{ $payouts->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
