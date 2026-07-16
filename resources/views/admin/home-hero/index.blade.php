{{-- resources/views/admin/home-hero/index.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Homepage Hero | Yuukke Dashboard')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Hero Section</h3>
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
                <a href="#">Hero</a>
            </li>
       
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Homepage Hero Sections</div>
                    <a href="{{ route('admin.home-hero.create') }}" class="btn btn-primary btn-sm">+ New Version</a>
                </div>
                <div class="card-body">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <p class="text-muted">
                        Only the version marked <strong>Active</strong> shows on the homepage. Keep old versions here as
                        drafts/history if you like.
                    </p>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Preview</th>
                                <th>Heading</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sections as $section)
                                <tr>
                                    <td style="width:70px;">
                                        @if ($section->image_main_url)
                                            <img src="{{ $section->image_main_url }}"
                                                style="width:60px;height:44px;object-fit:cover;border-radius:5px;">
                                        @endif
                                    </td>
                                    <td>
                                        {{ $section->heading }}
                                        @if ($section->heading_highlight)
                                            <strong>{{ $section->heading_highlight }}</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($section->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-light">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $section->updated_at->format('d M Y, H:i') }}</td>
                                    <td class="text-right">
                                        <a href="{{ route('admin.home-hero.edit', $section) }}"
                                            class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form action="{{ route('admin.home-hero.destroy', $section) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hero sections yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.delete-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const form = btn.closest('.delete-form');

                    Swal.fire({
                        title: 'Delete this hero section?',
                        text: 'This cannot be undone.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc3545',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
