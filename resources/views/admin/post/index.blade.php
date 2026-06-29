{{-- resources/views/admin/posts/index.blade.php --}}
@extends('admin.layouts.main')

@section('title', 'Posts | Yuukke Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title">Blog Posts</div>
                    <a href="{{ route('posts.create') }}" class="btn btn-success btn-sm">+ Add Post</a>
                </div>
                <div class="card-body">
                    @if (session('message'))
                        <div class="alert alert-{{ session('status', 'success') }} alert-dismissible fade show">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <table id="posts-table" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Published At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $id = 1;
                            @endphp
                            @forelse ($posts as $post)
                                <tr>
                                    <td>{{ $id++ }}</td>
                                    <td>
                                        @if ($post->featured_image)
                                            <img src="{{ $post->featured_image_url }}" alt="{{ $post->title }}"
                                                style="width:60px; height:60px; object-fit:cover; border-radius:6px;" />
                                        @else
                                            <span class="text-muted small">No image</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->title }}</td>
                                    <td><code>{{ $post->slug }}</code></td>
                                    <td>
                                        @if ($post->status === 'published')
                                            <span class="badge badge-success">Published</span>
                                        @else
                                            <span class="badge badge-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $post->published_at?->format('d M Y, h:i A') ?? '—' }}</td>
                                    <td>
                                        <a href="{{ route('posts.show', $post->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('posts.edit', $post->id) }}"
                                            class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                            class="delete-post" style="display:inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No posts yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- {{ $posts->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const dateColumnIndex = $('#posts-table thead th').length - 1;

            // Build per-column filter dropdowns/inputs in the tfoot row
            $('#posts-table tfoot th').each(function(i) {
                const headerCell = $('#posts-table thead th').eq(i);
                const fieldType = headerCell.data('type');
                const title = headerCell.text();

                if (i === 0 || i === dateColumnIndex) {
                    // Skip "#" column and date column (handled by date range inputs above)
                    $(this).html('');
                    return;
                }

                if (fieldType === 'select' || fieldType === 'checkbox') {
                    $(this).html(
                        '<select class="form-control form-control-sm column-filter"><option value="">All</option></select>'
                        );
                } else {
                    $(this).html(
                        `<input type="text" class="form-control form-control-sm column-filter" placeholder="Filter ${title}" />`
                        );
                }
            });

            const table = $('#posts-table').DataTable({
                order: [
                    [dateColumnIndex, 'desc']
                ],
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                dom: 'Bfrtip',
                buttons: [{
                    extend: 'colvis',
                    text: 'Show/Hide Columns'
                }],
                language: {
                    search: "Search posts:",
                    emptyTable: "No posts yet."
                },
                initComplete: function() {
                    const api = this.api();

                    $('#posts-table tfoot select.column-filter').each(function(i) {
                        const colIdx = $(this).closest('th').index();
                        const column = api.column(colIdx);
                        const select = $(this);

                        column.data().unique().sort().each(function(d) {
                            const text = $('<div>').html(d).text().trim();
                            if (text && !select.find(`option[value="${text}"]`)
                                .length) {
                                select.append(
                                    `<option value="${text}">${text}</option>`);
                            }
                        });

                        select.on('change', function() {
                            const val = $.fn.dataTable.util.escapeRegex($(this).val());
                            column.search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                        });
                    });

                    // Text filters
                    $('#posts-table tfoot input.column-filter').on('input', function() {
                        const colIdx = $(this).closest('th').index();
                        api.column(colIdx).search(this.value).draw();
                    });
                }
            });

            // Date range filtering — custom search function
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const from = $('#filter-date-from').val();
                const to = $('#filter-date-to').val();

                if (!from && !to) return true;

                const rowNode = table.row(dataIndex).node();
                const cellTimestamp = $(rowNode).find('td').eq(dateColumnIndex).data('order');
                if (!cellTimestamp) return true;

                const rowDate = new Date(cellTimestamp * 1000);
                const fromDate = from ? new Date(from) : null;
                const toDate = to ? new Date(to + 'T23:59:59') : null;

                if (fromDate && rowDate < fromDate) return false;
                if (toDate && rowDate > toDate) return false;

                return true;
            });

            $('#filter-date-from, #filter-date-to').on('change', function() {
                table.draw();
            });

            $('#clear-date-filter').on('click', function() {
                $('#filter-date-from, #filter-date-to').val('');
                table.draw();
            });
        });
    </script>

    <script>
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const post = btn.closest('.delete-post');

                Swal.fire({
                    title: 'Delete this post?',
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
