@extends('admin.layouts.main')

@section('content')

    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    Email Templates
                </h4>

                <p class="text-muted mb-0">
                    Manage your marketing email templates.
                </p>
            </div>


            <a href="{{ route('emails.templates.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i>
                Create Template
            </a>

        </div>


        {{-- MESSAGE STATUS --}}
        @if (session('message'))
            <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- Templates Table --}}
        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-hover align-middle">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Template</th>

                                <th>Subject</th>

                                <th>Category</th>

                                <th>Status</th>

                                <th>Created By</th>

                                <th>Created At</th>

                                <th class="text-end">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($templates as $template)
                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>


                                    <td>

                                        <strong>
                                            {{ $template->name }}
                                        </strong>

                                        @if ($template->description)
                                            <br>

                                            <small class="text-muted">

                                                {{ Str::limit($template->description, 60) }}

                                            </small>
                                        @endif

                                    </td>


                                    <td>
                                        {{ Str::limit($template->subject, 50) }}
                                    </td>


                                    <td>

                                        @if ($template->category)
                                            <span class="badge bg-light text-dark">

                                                {{ $template->category }}

                                            </span>
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif

                                    </td>


                                    <td>

                                        @if ($template->status === 'active')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>


                                    <td>

                                        {{ $template->creator?->name ?? 'System' }}

                                    </td>


                                    <td>

                                        {{ $template->created_at->format('d M Y') }}

                                    </td>


                                    <td class="text-end">

                                        <div class="btn-group gap-2">




                                            {{-- Edit --}}
                                            <a href="{{ route('emails.templates.edit', $template->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>


                                            {{-- Delete --}}
                                            <form action="{{ route('emails.templates.destroy', $template) }}"
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

                                    <td colspan="8" class="text-center py-5">

                                        <div class="text-muted">

                                            <i class="bi bi-envelope fs-1"></i>

                                            <p class="mt-2 mb-0">
                                                No email templates found.
                                            </p>

                                        </div>

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
        $('.delete-btn').on('click', function(e) {
            e.preventDefault();

            const post = $(this).closest('.delete-form');

            Swal.fire({
                title: 'Delete this page?',
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
    </script>
@endpush
