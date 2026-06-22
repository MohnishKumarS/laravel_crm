@extends('admin.layouts.main')

@section('title', 'Forms | Yuukke Dashboard')

@section('content')
    {{-- Breadcrumb --}}
    <div class="page-header">
        <h3 class="fw-bold mb-3">Form Page</h3>
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
                <a href="#">Forms</a>
            </li>
           
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Forms List</div>
                    <a href="{{ route('forms.create') }}" class="btn btn-success btn-sm">+ Add Form</a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <h5 class="alert alert-success">{{ session('success') }}</h5>
                    @endif

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Fields</th>
                                <th>Active</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $id = 1;
                            @endphp
                            @forelse ($forms as $form)
                                <tr>
                                    <td>{{ $id++ }}</td>
                                    <td>{{ $form->title }}</td>
                                    <td><code>{{ $form->slug }}</code></td>
                                    <td>{{ count($form->fields ?? []) }} field(s)</td>
                                    <td>
                                        @if ($form->active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $form->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('forms.show', $form->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('forms.edit', $form->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('forms.destroy', $form->id) }}" method="POST"
                                            style="display:inline-block" onsubmit="return confirm('Delete this form?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                         <a href="{{ route('forms.submissions', $form->id) }}"
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Fields</th>
                            <th>Active</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($forms as $form)
                            <tr>
                                <td>{{ $form->id }}</td>
                                <td>{{ $form->title }}</td>
                                <td><code>{{ $form->slug }}</code></td>
                                <td>{{ count($form->fields ?? []) }} field(s)</td>
                                <td>
                                    @if ($form->active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('forms.show', $form->id) }}"
                                       class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('forms.edit', $form->id) }}"
                                       class="btn btn-warning btn-sm">Edit</a>
                                  <form action="{{ route('forms.destroy', $form->id) }}" method="POST"
                                        class="delete-form" style="display:inline-block">
                                      @csrf
                                      @method('DELETE')
                                      <button type="button" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                  </form>
                                     <a href="{{ route('forms.submissions', $form->id) }}"
                                       class="btn btn-warning btn-sm">Submissions</a>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No forms created yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $forms->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
document.querySelectorAll('.delete-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const form = btn.closest('.delete-form');

        Swal.fire({
            title: 'Delete this form?',
            text: 'This cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545',
        }).then(function (result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush