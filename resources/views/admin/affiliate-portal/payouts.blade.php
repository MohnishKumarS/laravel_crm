@extends('admin.layouts.main')

@section('title', 'My Payouts')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">My Payouts</h3>
    </div>
    @if (!$affiliate->bank_account_number)
    <div class="alert alert-warning">
        <strong>Bank details required.</strong> You must add your bank details before any payout can be processed.
    </div>

    <div class="card mb-3">
        <div class="card-header"><div class="card-title">Add Bank Details</div></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('affiliate.payouts.bank-details') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">Account Holder Name</label>
                        <input type="text" name="bank_account_holder" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">Account Number</label>
                        <input type="text" name="bank_account_number" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">IFSC Code</label>
                        <input type="text" name="bank_ifsc" class="form-control" required>
                    </div>
                </div>
                <button class="btn btn-primary btn-sm">Save Bank Details</button>
            </form>
        </div>
    </div>
@else
    <div class="alert alert-success d-flex justify-content-between align-items-center">
        <span>Bank details on file: {{ $affiliate->bank_name }} — {{ $affiliate->maskedBankAccountNumber() }}</span>
        <button class="btn btn-sm btn-outline-secondary" type="button"
            onclick="document.getElementById('update-bank-form').classList.toggle('d-none')">
            Update
        </button>
    </div>
    <div id="update-bank-form" class="d-none card mb-3">
        <div class="card-body">
            <form method="POST" action="{{ route('affiliate.payouts.bank-details') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">Account Holder Name</label>
                        <input type="text" name="bank_account_holder" class="form-control" value="{{ $affiliate->bank_account_holder }}" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">Account Number</label>
                        <input type="text" name="bank_account_number" class="form-control" placeholder="Enter to change" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">Bank Name</label>
                        <input type="text" name="bank_name" class="form-control" value="{{ $affiliate->bank_name }}" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label class="small text-muted">IFSC Code</label>
                        <input type="text" name="bank_ifsc" class="form-control" value="{{ $affiliate->bank_ifsc }}" required>
                    </div>
                </div>
                <button class="btn btn-primary btn-sm">Update Bank Details</button>
            </form>
        </div>
    </div>
@endif

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
                                <td>{{ $loop->iteration }}</td>
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