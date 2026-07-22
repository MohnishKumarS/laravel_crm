@extends('admin.layouts.main')

@section('title', 'Affiliates | Yuukke Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="fw-bold mb-3">Affiliates</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="icon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Affiliate Program</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                    <div class="card-title mb-0">All Affiliates</div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('affiliates.create') }}" class="btn btn-primary btn-sm">Add Affiliate</a>
                        <a href="{{ route('affiliates.commissions') }}" class="btn btn-outline-secondary btn-sm">Commissions</a>
                        <a href="{{ route('affiliates.payouts') }}" class="btn btn-outline-secondary btn-sm">Payouts</a>
                        <a href="{{ route('affiliates.settings.edit') }}" class="btn btn-outline-secondary btn-sm">Settings</a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <h5 class="alert alert-success">{{ session('success') }}</h5>
                    @endif

                    <form method="GET" class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        <input type="text" name="search" class="form-control" style="max-width: 220px"
                            placeholder="Search name or code" value="{{ request('search') }}" />
                        <select name="status" class="form-control" style="max-width: 160px">
                            <option value="">All statuses</option>
                            @foreach (['pending', 'approved', 'suspended', 'rejected'] as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                    </form>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Rate</th>
                                <th>Pending</th>
                                <th>Approved (unpaid)</th>
                                <th>Lifetime Paid</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($affiliates as $affiliate)
                                <tr>
                                    <td>{{ $affiliate->affiliate_code }}</td>
                                    <td>{{ $affiliate->user->name ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $affiliate->status === 'approved' ? 'success' : ($affiliate->status === 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($affiliate->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $affiliate->commission_rate }}%</td>
                                    <td>{{ number_format($affiliate->pendingBalance(), 2) }}</td>
                                    <td>{{ number_format($affiliate->unpaidBalance(), 2) }}</td>
                                    <td>{{ number_format($affiliate->lifetime_paid, 2) }}</td>
                                    <td>
                                        <a href="{{ route('affiliates.show', $affiliate) }}" class="btn btn-sm btn-outline-primary">View</a>

                                        @if ($affiliate->status === 'pending')
                                            <form action="{{ route('affiliates.approve', $affiliate) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <button class="btn btn-sm btn-success">Approve</button>
                                            </form>
                                        @endif

                                        @if ($affiliate->status === 'approved')
                                            <form action="{{ route('affiliates.suspend', $affiliate) }}" method="POST" class="d-inline">
                                                @csrf @method('PUT')
                                                <button class="btn btn-sm btn-danger">Suspend</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $affiliates->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection