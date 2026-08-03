@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4>
                    {{ $campaign->name }}
                </h4>

                <p class="text-muted mb-0">

                    Template:
                    {{ $campaign->template?->name }}

                </p>

            </div>


            <div class="d-flex gap-2">

                @if ($campaign->status === 'queued')
                    <form action="{{ route('emails.campaigns.send', $campaign) }}" method="POST">
                        @csrf

                        <button type="submit" class="btn btn-outline-success">
                            <i class="fas fa-paper-plane me-1"></i>
                            Start Campaign
                        </button>
                    </form>
                @endif

                @if ($campaign->failed_count > 0)
                    <form action="{{ route('emails.campaigns.retry', $campaign) }}" method="POST">
                        @csrf

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-redo me-1"></i>
                            Retry Failed
                        </button>
                    </form>
                @endif
                <a href="{{ route('emails.campaigns.index') }}" class="btn btn-outline-secondary">

                    Back

                </a>
            </div>


        </div>

        {{-- MESSAGE STATUS --}}
        @if (session('message'))
            <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- Statistics --}}

        <div class="row mb-4">

            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Total
                        </small>

                        <h3 class="mb-0">

                            {{ number_format($campaign->total_recipients) }}

                        </h3>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Queued
                        </small>

                        <h3 class="mb-0 text-warning">

                            {{ number_format($campaign->queued_count) }}

                        </h3>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Sent
                        </small>

                        <h3 class="mb-0 text-success">

                            {{ number_format($campaign->sent_count) }}

                        </h3>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card border-0 shadow-sm">

                    <div class="card-body">

                        <small class="text-muted">
                            Failed
                        </small>

                        <h3 class="mb-0 text-danger">

                            {{ number_format($campaign->failed_count) }}

                        </h3>

                    </div>

                </div>

            </div>

        </div>


        {{-- Recipient table --}}

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white">

                <h5 class="mb-0">
                    Recipients
                </h5>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table class="table align-middle table-hover" id="campaignShowPagesTable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Email</th>

                                <th>Name</th>

                                <th>Sender</th>

                                <th>Status</th>

                                <th>Sent At</th>

                                <th>Error</th>

                            </tr>

                        </thead>


                        <tbody>
                            @php
                                $i = 1;
                            @endphp

                            @forelse($recipients as $recipient)
                                <tr>
                                    <td>{{ $i++ }}</td>

                                    <td>
                                        {{ $recipient->email }}
                                    </td>

                                    <td>
                                        {{ $recipient->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $recipient->sender_email ?? '-' }}
                                    </td>

                                    <td>

                                        @php

                                            $class = match ($recipient->status) {
                                                'sent' => 'bg-success',

                                                'failed' => 'bg-danger',

                                                'sending' => 'bg-info',

                                                default => 'bg-warning text-dark',
                                            };

                                        @endphp


                                        <span class="badge {{ $class }}">

                                            {{ ucfirst($recipient->status) }}

                                        </span>

                                    </td>

                                    <td>

                                        {{ $recipient->sent_at ? $recipient->sent_at->format('d M Y H:i:s') : '-' }}

                                    </td>

                                    <td>

                                        @if ($recipient->error_message)
                                            <small class="text-danger">

                                                {{ Str::limit($recipient->error_message, 80) }}

                                            </small>
                                        @else
                                            -
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6" class="text-center">
                                        No recipients found.
                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>


            </div>

        </div>

    </div>
@endsection



@push('scripts')
    <script>
        $('#campaignShowPagesTable').DataTable({
            pageLength: 25,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ]
        });
    </script>
@endpush
