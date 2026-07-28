@extends('admin.layouts.main')

@section('content')

    <div class="container-fluid">

        <div class="page-header">
            <h3 class="fw-bold mb-3">Dynamic Pages</h3>

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
                    <a href="{{ route('shop.dynamic-pages.index') }}">Dynamic Pages</a>
                </li>

                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

                <li class="nav-item">
                    <a href="#">List</a>
                </li>
            </ul>
        </div>


        {{-- Success Message --}}
        @if (session('message'))
            <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

            </div>
        @endif


        {{-- DataTable Card --}}
        <div class="card">

            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="card-title"> Dynamic Pages</div>
                <a href="{{ route('shop.dynamic-pages.create') }}" class="btn btn-primary btn-sm">+ Create Page</a>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="dynamicPagesTable" class="table table-bordered table-striped align-middle" width="100%">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Title</th>

                                <th>Subtitle</th>

                                <th>Heading</th>

                                <th>Page URL</th>

                                <th>Products Key</th>

                                <th>Status</th>

                                <th>Created At</th>

                                <th>Action</th>

                            </tr>

                        </thead>


                        <tbody>
                            @php
                                $i = 1;
                            @endphp

                            @forelse ($pages as $page)
                                <tr>

                                    {{-- ID --}}
                                    <td>
                                        {{ $i++ }}
                                    </td>


                                    {{-- Title --}}
                                    <td>
                                        <strong>
                                            {{ $page->title }}
                                        </strong>
                                    </td>


                                    {{-- Subtitle --}}
                                    <td>
                                        {{ $page->subtitle ?? '-' }}
                                    </td>


                                    {{-- Heading --}}
                                    <td>
                                        {{ $page->heading ?? '-' }}
                                    </td>


                                    {{-- Page URL --}}
                                    <td>

                                        <code>
                                            /{{ $page->page_url }}
                                        </code>

                                    </td>


                                    {{-- Products Key --}}
                                    <td>

                                        @if ($page->products_id)
                                            <span class="badge bg-info">
                                                {{ $page->products_id }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                -
                                            </span>
                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if ($page->status == '1')
                                            <span class="badge bg-success">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif

                                    </td>


                                    {{-- Created At --}}
                                    <td>

                                        {{ $page->created_at ? $page->created_at->format('d M Y') : '-' }}

                                    </td>


                                    {{-- Actions --}}
                                    <td>

                                        <div class="d-flex gap-1">

                                            {{-- Edit --}}
                                            <a href="{{ route('shop.dynamic-pages.edit', $page->id) }}"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>


                                            {{-- View Frontend --}}
                                            <a href="{{ config('app.frontend_url') . $page->page_url }}" target="_blank"
                                                class="btn btn-sm btn-info" title="View Page">
                                                <i class="fas fa-eye"></i>
                                            </a>


                                            {{-- Delete --}}
                                            <form action="{{ route('shop.dynamic-pages.destroy', $page->id) }}"
                                                method="POST" class="delete-form">

                                                @csrf

                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger delete-btn" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">No pages yet — create one to
                                        get
                                        started.</td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Laravel Pagination --}}
                {{-- <div class="mt-3">

                    {{ $pages->links() }}

                </div> --}}

            </div>

        </div>

    </div>

@endsection



@push('scripts')
    <script>
        $(document).ready(function() {

            $('#dynamicPagesTable').DataTable({});


            /*
            |--------------------------------------------------------------------------
            | Delete Confirmation
            |--------------------------------------------------------------------------
            */

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

        });
    </script>
@endpush
