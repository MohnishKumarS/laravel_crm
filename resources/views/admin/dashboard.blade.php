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
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="fas fa-wpforms"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Forms</p>
                                <h4 class="card-title">{{ $formsCount }}</h4>
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

    <div class="row">
        <div class="col-md-8">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Submissions Over Time (Last 12 Months)</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="min-height: 375px">
                        <canvas id="statisticsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-primary card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Today's Submissions</div>
                    </div>
                    <div class="card-category">{{ now()->format('d M Y') }}</div>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-4 mt-2">
                        <h1>{{ number_format($todaySubmissions) }}</h1>
                        <p class="text-muted mb-0">submissions received today</p>
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
    var ctx = document.getElementById('statisticsChart').getContext('2d');

    var statisticsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: "Form Submissions",
                borderColor: '#f3545d',
                pointBackgroundColor: 'rgba(243, 84, 93, 0.6)',
                pointRadius: 3,
                backgroundColor: 'rgba(243, 84, 93, 0.2)',
                fill: true,
                borderWidth: 2,
                data: @json($chartData)
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });
</script>
@endpush