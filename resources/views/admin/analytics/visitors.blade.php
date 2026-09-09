@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="page-header">
            <h4 class="page-title">Yuukke Analytics</h4>

            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>

                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

                <li class="nav-item">
                    <a href="{{ route('analytics.visitors') }}">Analytics</a>
                </li>

                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>

                <li class="nav-item">
                    Visitor Details
                </li>
            </ul>
        </div>

        <div class="card card-round">

            <div class="card-header">


                <div class="row justify-content-between">

                    <div class="col-md-4">
                        <div class="card-title">
                            Visitor Details
                        </div>

                        <div class="card-category">
                            Complete list of tracked visitors
                        </div>

                    </div>
                    <div class="col-md-4 col-lg-3">
                        <div class="row justify-content-end mt-4">
                            <div class="col-6">
                                <select id="monthFilter" class="form-select">

                                    @foreach ($months as $month)
                                        <option value="{{ $month['value'] }}"
                                            {{ $selectedMonth == $month['value'] ? 'selected' : '' }}>

                                            {{ $month['label'] }}

                                        </option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="col-6">
                                <a href="{{ route('analytics.visitors.export', ['month' => $selectedMonth]) }}"
                                    class="btn btn-success btn-sm">

                                    <i class="fa fa-file-excel"></i>

                                    Export Excel

                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="analyticsTabs">

                    <li class="nav-item ">
                        <a class="nav-link active" data-bs-toggle="tab" type="button" data-bs-target="#pages">
                            Top Pages
                            <span class="badge badge-warning">{{ $topPages->count() }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#countries">
                            Countries
                            <span class="badge badge-info">{{ $countryStats->count() }}</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#devices">
                            Devices
                            <span class="badge badge-success">{{ $deviceStats->count() }}</span>
                        </a>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#visitors">
                            All Visitors
                            <span class="badge badge-primary">{{ $totalVisitors }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#notfoundpages">
                            page Not Found
                            <span class="badge badge-warning">{{ $notFoundPages->count() }}</span>
                        </a>
                    </li>

                </ul>

                <div class="tab-content">

                    {{-- Visitors --}}
                    <div class="tab-pane fade " id="visitors">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="visitorTable" style="width: 100%">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Visitor ID</th>

                                        <th>Country</th>

                                        <th>State</th>

                                        <th>City</th>

                                        {{-- <th>Browser</th>

                                        <th>Device</th> --}}

                                        <th>Visits</th>

                                        <th>First Visit</th>

                                        <th>Last Visit</th>

                                        {{-- <th>Page Views</th> --}}

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    {{-- @foreach ($visitors as $visitor)
                                        <tr class="visitor-row" style="cursor: pointer;"
                                            data-visitor-id="{{ $visitor->id }}">

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $visitor->visitor_id }}</td>

                                            <td>{{ $visitor->country ?: '-' }}</td>

                                            <td>{{ $visitor->state ?: '-' }}</td>

                                            <td>{{ $visitor->city ?: '-' }}</td>

                                            <td>{{ number_format($visitor->visit_count) }}</td>

                                            <td>{{ $visitor->first_visit }}</td>

                                            <td>{{ $visitor->last_visit }}</td>

                                            <td>

                                                @if ($visitor->last_visit >= now()->subMinutes(5))
                                                    <span class="badge badge-success">
                                                        Active
                                                    </span>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        Offline
                                                    </span>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach --}}

                                </tbody>
                            </table>
                        </div>
                        {{-- {{ $visitors->links() }} --}}
                    </div>

                    {{-- Countries --}}
                    <div class="tab-pane fade" id="countries">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="countryTable">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Country</th>
                                        <th>State</th>
                                        <th>City</th>
                                        {{-- <th>Country</th> --}}

                                        <th>Visitors</th>

                                        <th>%</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($countryStats as $country)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $country->country }}</td>
                                            <td>{{ $country->state }}</td>
                                            <td>{{ $country->city }}</td>

                                            <td>{{ number_format($country->total) }}</td>

                                            <td>{{ $country->percentage }}%</td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>

                    {{-- Devices --}}
                    <div class="tab-pane fade" id="devices">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="deviceTable">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Device</th>

                                        <th>Visitors</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($deviceStats as $device)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ ucfirst($device->device) }}</td>

                                            <td>{{ number_format($device->total) }}</td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>

                    {{-- Pages --}}
                    <div class="tab-pane fade show active" id="pages">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="pageTable">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Page Title</th>

                                        <th>Page Url</th>

                                        <th>Views</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($topPages as $page)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $page->page_title }}</td>
                                            <td>{{ $page->page_url }}</td>

                                            <td>{{ number_format($page->total) }}</td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>

                    {{-- Page Not Found --}}
                    <div class="tab-pane fade" id="notfoundpages">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="pageNotFoundTable">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        {{-- <th>Page Title</th> --}}

                                        <th>Page Url</th>

                                        <th>country</th>
                                        <th>Visit from</th>

                                        <th>Date</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($notFoundPages as $page)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            {{-- <td>{{ $page->page_title }}</td> --}}
                                            <td>{{ $page->page_url }}</td>

                                            <td>{{ $page->visitor->country }}</td>
                                            <td>{{ $page->referrer }}</td>

                                            <td>{{ $page->created_at }} ( {{ $page->created_at->diffForHumans() }} )</td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>



    </div>
@endsection

@push('scripts')
    <script>
        // $('#visitorTable').DataTable();

        $('#countryTable').DataTable({});

        $('#deviceTable').DataTable({});

        $('#pageTable').DataTable({});
        $('#pageNotFoundTable').DataTable({});

        $("#monthFilter").change(function() {

            window.location =
                "{{ route('analytics.visitors') }}" +
                "?month=" + $(this).val();

        });
        // const visitorTable = $('#visitorTable').DataTable();

        const visitorTable = $('#visitorTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('analytics.visitors.data') }}",
                data: function(d) {
                    d.month = $('#monthFilter').val(); // if month filter exists
                }
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'visitor_id',
                    name: 'visitor_id'
                },
                {
                    data: 'country',
                    name: 'country'
                },
                {
                    data: 'state',
                    name: 'state'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'visit_count',
                    name: 'visit_count'
                },
                {
                    data: 'first_visit',
                    name: 'first_visit'
                },
                {
                    data: 'last_visit',
                    name: 'last_visit'
                },
                {
                    data: 'status',
                    name: 'status',
                    searchable: false,
                    orderable: false
                }
            ],
            order: [[7, 'desc']],
            createdRow: function(row, data) {
                $(row)
                    .addClass('visitor-row')
                    .attr('data-visitor-id', data.visitor_db_id)
                    .css('cursor', 'pointer');
            },
            pageLength: 25
        });

        $('#visitorTable tbody').on('click', 'tr', function() {

            // const data = visitorTable.row(this).data();

            // const visitorId = data.visitor_db_id;

            // console.log(data); return ;

            const row = visitorTable.row(this);
            const data = visitorTable.row(this).data();
            const visitorId = data.visitor_db_id;

            if (row.child.isShown()) {

                row.child.hide();

                $(this)
                    .removeClass('shown')
                    .find('.visitor-arrow')
                    .removeClass('fa-chevron-down')
                    .addClass('fa-chevron-right');

                return;
            }

            // Show loading message
            row.child(`
            <div class="p-3 text-center">
                <i class="fas fa-spinner fa-spin"></i>
                Loading page views...
            </div>
        `).show();

            $(this)
                .addClass('shown')
                .find('.visitor-arrow')
                .removeClass('fa-chevron-right')
                .addClass('fa-chevron-down');

            const pageViewUrl = "{{ url('visitors') }}";

            $.ajax({
                url: `${pageViewUrl}/${visitorId}/page-views`,
                type: 'GET',

                success: function(response) {

                    //     if (!response.length) {
                    //         row.child(`
                //     <div class="p-3 text-muted">
                //         No page views found.
                //     </div>
                // `).show();

                    //     return;
                    // }

                    const visitor = response.visitor;
                    const pageViews = response.page_views;

                    let html = `
        <div class="p-3">

            <div class="card border-0 shadow mb-3">
                <div class="card-header bg-light">
                    <strong>Visitor Journey Summary</strong>
                </div>

                <div class="card-body py-3">
                    <div class="row">

                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Entry Point</small>
                            <strong>${visitor.entry_point ?? '-'}</strong>
                        </div>

                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Campaign</small>
                            <strong>${visitor.campaign ?? 'Direct Visit'}</strong>
                        </div>

                        <div class="col-md-6 mb-2">
                            <small class="text-muted d-block">Device</small>
                            <span class="badge badge-info">
                                ${visitor.device ?? '-'}
                            </span>
                                </div>

                                <div class="col-md-6 mb-2">
                                    <small class="text-muted d-block">Browser</small>
                                    <strong>${visitor.browser ?? '-'}</strong>
                                </div>

                            </div>
                        </div>
                    </div>

                    <h6 class="mt-3 mb-2">
                        <i class="fas fa-history mr-1"></i>
                        Page View History
                    </h6>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th width="25%">Page Title</th>
                                    <th width="45%">Page URL</th>
                                    <th width="30%">Previous Visit</th>
                                </tr>
                            </thead>
                            <tbody>
`;
                    pageViews.forEach(function(page) {

                        html += `
        <tr>
            <td>${page.page_title ?? '-'}</td>

            <td>
                <a href="${page.page_url}"
                   target="_blank"
                   class="text-primary">
                    ${page.page_url ?? '-'}
                </a>
            </td>

            <td>
                ${page.referrer ?? '-'}
            </td>
        </tr>
    `;
                    });

                    html += `
                        </tbody>
                    </table>
                </div>
                </div>
            `;

                    row.child(html).show();
                },

                error: function() {

                    row.child(`
                <div class="p-3 text-danger">
                    Failed to load page views.
                </div>
            `).show();
                }
            });
        });
    </script>
@endpush
