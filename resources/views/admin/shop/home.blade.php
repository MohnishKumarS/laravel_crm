@extends('admin.layouts.main')

@section('content')
    <div>
        <div>
            <h3 class="fw-bold mb-3">Shop Analytics</h3>
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

        {{--  DEVICE --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-title">
                            Device Distribution
                        </div>
                        <div class="card-category">
                            Visitors by Device
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="chart-container" style="height:350px;">
                            <canvas id="deviceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- COUNTRY --}}
        <div class="row">
            <div class="col-md-12">
                <div class="card card-round">
                    <div class="card-header">
                        <div class="card-head-row card-tools-still-right">
                            <h4 class="card-title">Users Geolocation</h4>
                            <div class="card-tools">
                                {{-- <button class="btn btn-icon btn-link btn-primary btn-xs">
                                <span class="fa fa-angle-down"></span>
                            </button> --}}
                                <button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card">
                                    <span class="fa fa-sync-alt"></span>
                                </button>
                                <button class="btn btn-icon btn-link btn-primary btn-xs">
                                    <span class="fa fa-times"></span>
                                </button>
                            </div>
                        </div>
                        <p class="card-category">
                            Map of the distribution of users around the world
                        </p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="table-responsive table-hover table-sales">
                                    <div class="table-scroll">
                                        <table class="table">
                                            <tbody>
                                                @foreach ($countryStats as $country)
                                                    <tr>

                                                        <td>
                                                            <div class="flag">

                                                                <img src="{{ asset('yuukke/assets/img/flags/' . ($countryCodes[$country->country] ?? 'unknown') . '.png') }}"
                                                                    width="24">

                                                            </div>
                                                        </td>

                                                        <td>{{ $country->country }}</td>

                                                        <td class="text-end">
                                                            {{ number_format($country->total) }}
                                                        </td>

                                                        <td class="text-end">
                                                            {{ $country->percentage }}%
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mapcontainer">
                                    <div id="world-map" class="w-100" style="height: 300px"></div>
                                </div>
                            </div>
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
        </div>

    </div>
@endsection


@push('scripts')
    <script>
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


        function loadVisitorsChart(month) {

            $.ajax({

                url: "{{ route('shop.visitors-per-day') }}",

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


        // Device Distribution
        var deviceCtx = document.getElementById("deviceChart").getContext("2d");

        new Chart(deviceCtx, {

            type: "pie",

            data: {

                labels: @json($deviceLabels),

                datasets: [{

                    data: @json($deviceCounts),

                    backgroundColor: [
                        "#1572E8",
                        "#31CE36",
                        "#FFAD46",
                        "#F25961",
                        "#6F42C1",
                        "#20C997",
                        "#FD7E14"
                    ],

                    borderWidth: 1
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                legend: {
                    position: "bottom"
                }
            }

        });


        // Jsvectormap Geolocation
        var world_map = new jsVectorMap({
            selector: "#world-map",
            map: "world",
            zoomOnScroll: false,
            regionStyle: {
                hover: {
                    fill: '#1572E8'
                }
            },
            markers: @json($markers),
            onRegionTooltipShow(event, tooltip) {
                tooltip.css({
                    backgroundColor: '#435ebe'
                })
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
    </script>
@endpush

@push('styles')
    <style>
        .table-scroll {
            max-height: 400px;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .table-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: #bbb;
            border-radius: 4px;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: #888;
        }
    </style>
@endpush
