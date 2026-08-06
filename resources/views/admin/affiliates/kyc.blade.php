@extends('admin.layouts.main')

@section('title', 'Affiliate KYC Review')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">KYC Review Queue</h3>
    </div>

    @if (session('success'))
        <h5 class="alert alert-success">{{ session('success') }}</h5>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Partner</th>
                            <th>Type</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Bank Details</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $doc)
                            <tr>
                                <td>{{ $doc->affiliate->affiliate_code }}</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</td>
                                <td><a href="{{ route('affiliates.kyc.download', $doc) }}">Download</a></td>
                                <td>
                                    <span
                                        class="badge badge-{{ $doc->status === 'approved' ? 'success' : ($doc->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($doc->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small>
                                        {{ $doc->affiliate->bank_account_holder ?? '—' }}<br>
                                        {{ $doc->affiliate->bank_name ?? '—' }}<br>
                                        A/C: {{ $doc->affiliate->maskedBankAccountNumber() ?? '—' }}<br>
                                        IFSC: {{ $doc->affiliate->bank_ifsc ?? '—' }}
                                    </small>
                                </td>
                                <td>
                                    @if ($doc->status === 'pending')
                                        <div class="d-flex flex-column gap-1" style="min-width: 100px;">
                                            <form method="POST" action="{{ route('affiliates.kyc.review', $doc) }}">
                                                @csrf @method('PUT')
                                                <button name="status" value="approved" class="btn btn-success btn-sm w-100"
                                                    onclick="return confirm('Approve this document?')">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('affiliates.kyc.review', $doc) }}">
                                                @csrf @method('PUT')
                                                <button name="status" value="rejected" class="btn btn-danger btn-sm w-100"
                                                    onclick="return confirm('Reject this document?')">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted small">{{ $doc->reviewed_at?->format('Y-m-d') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $documents->links() }}
        </div>
    </div>
@endsection
