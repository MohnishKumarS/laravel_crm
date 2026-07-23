@extends('admin.layouts.main')

@section('content')
    <div class="page-inner">

        <div class="page-header">
            <h4 class="page-title">Marketplace Analytics</h4>

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
                    <a href="{{ route('analytics.shop') }}">Analytics</a>
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
                                <a href="{{ route('analytics.shop.export', ['month' => $selectedMonth]) }}"
                                    class="btn btn-success btn-sm">

                                    <i class="fa fa-file-excel"></i>

                                    Export Excel

                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            {{-- <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-striped table-bordered" id="visitorTable">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Visitor ID</th>

                                <th>Country</th>

                                <th>State</th>

                                <th>City</th>

                                <th>Browser</th>

                                <th>Device</th>

                                <th>Visits</th>

                                <th>First Visit</th>

                                <th>Last Visit</th>

                                <th>Page Views</th>

                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($visitors as $visitor)
                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $visitor->visitor_id }}</td>

                                    <td>{{ $visitor->country ?: '-' }}</td>

                                    <td>{{ $visitor->state ?: '-' }}</td>

                                    <td>{{ $visitor->city ?: '-' }}</td>

                                    <td>{{ $visitor->browser }}</td>

                                    <td>{{ $visitor->device }}</td>

                                    <td>{{ number_format($visitor->visit_count) }}</td>

                                    <td>{{ $visitor->first_visit }}</td>

                                    <td>{{ $visitor->last_visit }}</td>

                                    <td>{{ number_format($visitor->page_views_count) }}</td>

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
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div> --}}
            <div class="card-body">
                <ul class="nav nav-tabs mb-4" id="analyticsTabs">

                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" type="button" data-bs-target="#visitors">
                            All Visitors
                            <span class="badge badge-primary">{{ $visitors->count() }}</span>
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
                        <a class="nav-link" data-bs-toggle="tab" type="button" data-bs-target="#pages">
                            Top Pages
                            <span class="badge badge-warning">{{ $topPages->count() }}</span>
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
                    <div class="tab-pane fade show active" id="visitors">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="visitorTable">

                                <thead>

                                    <tr>

                                        <th>#</th>

                                        <th>Visitor ID</th>

                                        <th>Country</th>

                                        <th>State</th>

                                        <th>City</th>

                                        <th>Browser</th>

                                        <th>Device</th>

                                        <th>Visits</th>

                                        <th>First Visit</th>

                                        <th>Last Visit</th>

                                        <th>Page Views</th>

                                        <th>Status</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($visitors as $visitor)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $visitor->visitor_id }}</td>

                                            <td>{{ $visitor->country ?: '-' }}</td>

                                            <td>{{ $visitor->state ?: '-' }}</td>

                                            <td>{{ $visitor->city ?: '-' }}</td>

                                            <td>{{ $visitor->browser }}</td>

                                            <td>{{ $visitor->device }}</td>

                                            <td>{{ number_format($visitor->visit_count) }}</td>

                                            <td>{{ $visitor->first_visit }}</td>

                                            <td>{{ $visitor->last_visit }}</td>

                                            <td>{{ number_format($visitor->page_views_count) }}</td>

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
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
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
                    <div class="tab-pane fade" id="pages">
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

                                        <th>Page Title</th>

                                        <th>Page Url</th>

                                        <th>country</th>

                                        <th>Date</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($notFoundPages as $page)
                                        <tr>

                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $page->page_title }}</td>
                                            <td>{{ $page->page_url }}</td>

                                            <td>{{ $page->visitor->country }}</td>

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
        // $('#visitorTable').DataTable({

        //     pageLength: 25,

        //     order: [
        //         [9, 'desc']
        //     ],

        //     responsive: true,

        //     dom: 'Bfrtip',

        //     buttons: [

        //         'copy',

        //         'csv',

        //         'excel',

        //         'pdf',

        //         'print'

        //     ]

        // });

        $('#visitorTable').DataTable();

        $('#countryTable').DataTable({});

        $('#deviceTable').DataTable({});

        $('#pageTable').DataTable({});
        $('#pageNotFoundTable').DataTable({});

        $("#monthFilter").change(function() {

            window.location =
                "{{ route('analytics.shop') }}" +
                "?month=" + $(this).val();

        });
    </script>
@endpush
