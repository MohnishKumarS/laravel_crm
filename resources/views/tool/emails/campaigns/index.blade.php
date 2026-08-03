@extends('admin.layouts.main')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4>
                    Email Campaigns
                </h4>

                <p class="text-muted mb-0">
                    Manage your marketing email campaigns.
                </p>

            </div>


            <a href="{{ route('emails.campaigns.create') }}" class="btn btn-primary">

                <i class="bi bi-plus-lg"></i>

                Create Campaign

            </a>

        </div>


        {{-- MESSAGE STATUS --}}
        @if (session('message'))
            <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle" id="campaignPagesTable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Campaign</th>

                                <th>Template</th>

                                <th>Recipients</th>

                                <th>Sent</th>

                                <th>Failed</th>

                                <th>Status</th>

                                <th>Created</th>

                                <th class="text-end">
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($campaigns as $campaign)
                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $campaign->name }}
                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ ucfirst($campaign->recipient_type) }}

                                        </small>

                                    </td>


                                    <td>

                                        {{ $campaign->template?->name ?? '-' }}

                                    </td>


                                    <td>

                                        <span class="badge bg-primary">

                                            {{ number_format($campaign->total_recipients) }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="text-success">

                                            {{ number_format($campaign->sent_count) }}

                                        </span>

                                    </td>


                                    <td>

                                        <span class="text-danger">

                                            {{ number_format($campaign->failed_count) }}

                                        </span>

                                    </td>


                                    <td>

                                        @php

                                            $statusClass = match ($campaign->status) {
                                                'processing' => 'bg-info',
                                                'completed' => 'bg-success',
                                                'failed' => 'bg-danger',
                                                'queued' => 'bg-warning text-dark',
                                                'draft' => 'bg-secondary',
                                                default => 'bg-secondary',
                                            };

                                        @endphp


                                        <span class="badge {{ $statusClass }}">

                                            {{ ucfirst($campaign->status) }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $campaign->created_at->format('d M Y H:i') }}

                                    </td>


                                    <td class="text-end">
                                        <div class="btn-group gap-2">

                                            <a href="{{ route('emails.campaigns.show', $campaign) }}"
                                                class="btn btn-sm btn-outline-primary">

                                                <i class="fas fa-eye"></i>

                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('emails.campaigns.destroy', $campaign->id) }}"
                                                method="POST" class="d-inline delete-form">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-outline-danger delete-btn"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </form>
                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="9" class="text-center py-5 text-muted">

                                        No email campaigns found.

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
        $('#campaignPagesTable').DataTable({});


        $('.delete-btn').on('click', function(e) {
            e.preventDefault();

            const post = $(this).closest('.delete-form');

            Swal.fire({
                title: 'Delete this campaigns?',
                text: 'This cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545',
            }).then(function(result) {
                if (result.isConfirmed) {
                    post.submit();
                }
            });
        });
    </script>
@endpush
