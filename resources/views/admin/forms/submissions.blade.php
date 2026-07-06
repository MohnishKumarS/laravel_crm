{{-- resources/views/admin/forms/submissions.blade.php --}}
@extends('admin.layouts.main')

@section('title', $form->title . ' Submissions | Yuukke Dashboard')

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
                <a href="{{ route('forms.index') }}">Forms</a>
            </li>
            <li class="separator">
                <i class="icon-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">List</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <div class="card-title">{{ $form->title }} — Submissions ({{ $form->submissions->count() }})</div>
                    <div>
                        <a href="{{ route('forms.submissions.export', $form->id) }}" class="btn btn-success btn-sm">
                            <i class="fa fa-file-excel-o"></i> Export to Excel
                        </a>
                        <a href="{{ route('forms.index') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
                <div class="card-body">

                    {{-- Date range filter --}}
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="small mb-1">From date</label>
                            <input type="date" id="filter-date-from" class="form-control form-control-sm" />
                        </div>
                        <div class="col-md-3">
                            <label class="small mb-1">To date</label>
                            <input type="date" id="filter-date-to" class="form-control form-control-sm" />
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button id="clear-date-filter" class="btn btn-outline-secondary btn-sm">Clear dates</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="submissions-table" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    @foreach ($form->fields as $field)
                                        @if (!empty($field['name']))
                                            <th data-type="{{ $field['type'] }}">{{ $field['label'] ?? $field['name'] }}
                                            </th>
                                        @endif
                                    @endforeach
                                    <th data-type="date">Submitted At</th>
                                    <th>
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            {{-- Column filter row --}}
                            <tfoot>
                                <tr>
                                    <th></th>
                                    @foreach ($form->fields as $field)
                                        @if (!empty($field['name']))
                                            <th></th>
                                        @endif
                                    @endforeach
                                    <th></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @php
                                    $id = 1;
                                @endphp
                                @foreach ($form->submissions as $submission)
                                    <tr>
                                        <td>{{ $id++ }}</td>
                                        @foreach ($form->fields as $field)
                                            @if (!empty($field['name']))
                                                <td>
                                                    @php $value = $submission->data[$field['name']] ?? ''; @endphp
                                                    @if (is_array($value))
                                                        {{ implode(', ', $value) }}
                                                    @elseif (is_bool($value))
                                                        {{ $value ? 'Yes' : 'No' }}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                        <td data-order="{{ $submission->created_at->timestamp }}">
                                            {{ $submission->created_at->format('d M Y, h:i A') }}
                                        </td>
                                        <td>

                                            <form action="{{ route('forms.submissions.delete', $submission->id) }}"
                                                method="POST" class="delete-post" style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-danger btn-sm delete-btn">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            const dateColumnIndex = $('#submissions-table thead th').length - 2;
            const actionColumnIndex = $('#submissions-table thead th').length - 1;

            // Build per-column filter dropdowns/inputs in the tfoot row
            $('#submissions-table tfoot th').each(function(i) {
                const headerCell = $('#submissions-table thead th').eq(i);
                const fieldType = headerCell.data('type');
                const title = headerCell.text();

                if (i === 0 || i === dateColumnIndex || i === actionColumnIndex) {
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

            const table = $('#submissions-table').DataTable({
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
                    search: "Search submissions:",
                    emptyTable: "No submissions yet."
                },
                initComplete: function() {
                    const api = this.api();

                    $('#submissions-table tfoot select.column-filter').each(function(i) {
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
                    $('#submissions-table tfoot input.column-filter').on('input', function() {
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
                    title: 'Delete this submission?',
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
