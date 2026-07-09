@extends('admin.layouts.main')

@section('content')
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <a href="{{ route('forms.index') }}" class="text-decoration-none text-dark">
                        <div class="row align-items-center">

                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Forms</p>
                                    <h4 class="card-title">{{ $formsCount }}</h4>
                                </div>
                            </div>

                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <a href="{{ route('posts.index') }}" class="text-decoration-none text-dark">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                                <div class="numbers">
                                    <p class="card-category">Posts</p>
                                    <h4 class="card-title">{{ $postsCount }}</h4>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                <i class="fas fa-inbox"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Form Submissions</p>
                                <h4 class="card-title">{{ $submissionsCount }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <h3 class="fw-bold mb-3">Analytics</h3>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                <i class="fas fa-globe"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Visitors</p>
                                <h4 class="card-title">{{ $totalVisitors }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-black bubble-shadow-small">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Today's Visitors</p>
                                <h4 class="card-title">{{ $todayVisitors }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="fas fa-heartbeat"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Active Visitors</p>
                                <h4 class="card-title">{{ $activeVisitors }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- VISITORS GRAPH OVERVIEW --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-round">
                <div class="card-header">
                        <div class="card-title">Monthly Visitor Statistics</div>
                        {{-- <div class="card-category">Total Visitors by Month (Last 12 Months)</div> --}}
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="visitorsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Daily Traffic Overview (For 30 Days)</div>

                        <div class="card-tools">
                            <select id="monthFilter" class="form-select form-control-md">
                                @foreach ($months as $month)
                                    <option value="{{ $month['value'] }}" {{ $month['selected'] ? 'selected' : '' }}>
                                        {{ $month['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="visitorsDailyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MOST VIEWS PAGES CHART --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Top Pages</div>
                    <div class="card-category">Most Viewed Pages</div>
                </div>

                <div class="card-body">
                    <div class="chart-container" style="min-height:450px;">
                        <canvas id="topPagesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-md-4">
            <div class="card card-warning card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">
                            Most Viewed Page
                        </div>
                    </div>

                    <div class="card-category">
                        {{ now()->format('d M Y') }}
                    </div>
                </div>

                <div class="card-body pb-0">

                    @if ($mostViewedPage)
                        <div class="mb-4 mt-2">

                            <h2>{{ $mostViewedPage->page_title }}</h2>

                            <p class="mb-1 text-muted">
                                {{ $mostViewedPage->page_url }}
                            </p>

                            <h3 class="mt-3">
                                {{ number_format($mostViewedPage->total_views) }}
                            </h3>

                            <p class="text-muted">
                                Total Views
                            </p>

                        </div>
                    @else
                        <h4>No page views found.</h4>
                    @endif

                </div>
            </div>
        </div> --}}
    </div>


    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-title">Submissions Over Time</div>
                    <div class="card-category">Monthly Submission Performance (Last 12 Months)</div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-secondary card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Today's Submissions</div>
                    </div>
                    <div class="card-category">{{ now()->format('d M Y') }}</div>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                        <h1>{{ number_format($todaySubmissions) }}</h1>
                        <p class="mb-0">submissions received today</p>
                    </div>
                </div>
            </div>
            <div class="card card-round">
                <div class="card-body pb-0">
                    <h2 class="mb-2">{{ $submissionsCount }}</h2>
                    <p class="text-muted">Total submissions all-time</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function loadVisitorsChart(month) {

            $.ajax({

                url: "{{ route('dashboard.visitors-per-day') }}",

                type: "GET",

                data: {

                    month: month
                },

                success: function(response) {

                    // console.log(response.labels);
                    // return;


                    visitorsDailyChart.data.labels = response.labels;

                    visitorsDailyChart.data.datasets[0].data = response.data;

                    visitorsDailyChart.update();
                }
            });

        }

        $(document).ready(function() {

            loadVisitorsChart($("#monthFilter").val());

        });

        $("#monthFilter").on("change", function() {
            // console.log($(this).val());

            loadVisitorsChart($(this).val());

        });







        // Visitors Monthly chart
        var ctx = document.getElementById('visitorsChart').getContext('2d');

        var statisticsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                        label: "Visitors",
                        borderColor: '#f3545d',
                        pointBackgroundColor: 'rgba(243, 84, 93, 0.6)',
                        backgroundColor: 'rgba(243, 84, 93, 0.2)',
                        fill: true,
                        pointRadius: 3,
                        borderWidth: 2,
                        data: @json($visitorData)
                    },
                    // {
                    //     label: "Page Views",
                    //     data: @json($pageViewsData),
                    //     borderColor: "#31CE36",
                    //     backgroundColor: "rgba(49,206,54,.1)",
                    //     fill: false
                    // }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });

        // Visitors Daily chart -  Daily Traffic Overview
        var ctx = document.getElementById('visitorsDailyChart').getContext('2d');

        var visitorsDailyChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: "Visitors",
                    data: [],
                    borderColor: "#14B8A6",
                    backgroundColor: "rgba(20,184,166,0.15)",
                    pointBackgroundColor: "#14B8A6",
                    pointRadius: 3,
                    borderWidth: 2,
                    fill: true,
                    // tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: true
                    }
                },

                scales: {
                    // xAxes: [{
                    //     ticks: {
                    //         autoSkip: false
                    //     }
                    // }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });


        // TOPpages BAR CHART
        var topPageBar = document.getElementById('topPagesChart').getContext('2d');

        const pageLabels = @json($topPageLabels);
        const pageViewData = @json($topPageViews);
        const colors = [
            '#1572E8',
            '#31CE36',
            '#FFAD46',
            '#F25961',
            '#6F42C1',
            '#20C997',
            '#FD7E14',
            '#0DCAF0',
            '#198754',
            '#DC3545'
        ];


        var topPagesChart = new Chart(topPageBar, {
            type: 'bar',
            data: {
                labels: pageLabels,
                datasets: [{
                    label: "Page Views",
                    data: pageViewData,
                    backgroundColor: pageViewData.map((_, i) => colors[i % colors.length]),
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',

                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    }
                },

                scales: {
                    xAxes: [{
                        ticks: {
                            autoSkip: false
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });


        // Submission Chart
        var ctx = document.getElementById('statisticsChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: "Form Submissions",
                    borderColor: "#1572E8",
                    backgroundColor: "rgba(21,114,232,0.15)",
                    pointBackgroundColor: "#1572E8",
                    pointRadius: 3,
                    borderWidth: 2,
                    fill: true,
                    data: @json($chartData)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }]
                }
            }
        });
    </script>
@endpush
