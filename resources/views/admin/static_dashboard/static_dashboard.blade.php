@extends('admin.layouts.master')

@section('main-section')
    <div class="container-fluid pt-3 px-4">

        <div class="greeting-banner mb-4">
            <div class="date-pill">
                <i class="fa fa-calendar-alt me-2"></i>
            </div>
            <h3 class="text-black fw-bold mt-3 mb-1">
                Static Dashboard
            </h3>

        </div>

        {{-- Stat Cards Row --}}
        <div class="row g-3 mb-4">

            {{-- Faculties Card --}}
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('view_faculty') }}" class="text-decoration-none">
                    <div class="stat-card border-primary">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-primary-soft text-primary">
                                <i class="fa fa-chalkboard-teacher"></i>
                            </div>
                            <span class="badge-tag">Active</span>
                        </div>
                        <h2 class="stat-value mt-2 mb-0">{{ $facultiesCount }}</h2>
                        <p class="stat-label mb-0">Faculties</p>
                    </div>
                </a>
            </div>

            {{-- Programmes Card --}}
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('view_prog_list') }}" class="text-decoration-none">
                    <div class="stat-card border-success">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="stat-icon bg-success-soft text-success">
                                <i class="fa fa-book"></i>
                            </div>
                            <span class="badge-tag">{{ now()->format('Y') }}</span>
                        </div>
                        <h2 class="stat-value mt-2 mb-0">{{ $programmesCount }}</h2>
                        <p class="stat-label mb-0">Programmes</p>
                    </div>
                </a>
            </div>

            {{-- Nominations Card --}}
            <div class="col-lg-3 col-md-6">
                <div class="stat-card border-warning">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="stat-icon bg-warning-soft text-warning">
                            <i class="fa fa-award"></i>
                        </div>
                        <span class="badge-tag">{{ now()->format('Y') }}</span>
                    </div>
                    <h2 class="stat-value mt-2 mb-0">{{ $nominationsCount }}</h2>
                    <p class="stat-label mb-0">Nominations</p>
                </div>
            </div>

            {{-- Programme Status Card --}}
            <div class="col-lg-3 col-md-6">
                <div class="stat-card border-info status-summary-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="stat-icon bg-info-soft text-info">
                            <i class="fa fa-tasks"></i>
                        </div>
                        <span class="badge-tag">{{ now()->format('Y') }}</span>
                    </div>

                    <div class="status-summary-body mt-2">
                        <p class="stat-label status-summary-title mb-0">Programme Status</p>


                        <div class="status-mini-group">
                            <div class="status-mini-row">
                                <span class="status-mini-dot bg-success"></span>
                                <span class="status-mini-label">Announced-</span>
                                <span class="status-mini-count">{{ $announcedCount }}</span>
                            </div>
                            <div class="status-mini-row">
                                <span class="status-mini-dot bg-warning"></span>
                                <span class="status-mini-label">Postponed-</span>
                                <span class="status-mini-count">{{ $postponedCount }}</span>
                            </div>

                            <div class="status-mini-row">
                                <span class="status-mini-dot bg-danger"></span>
                                <span class="status-mini-label">Cancelled-</span>
                                <span class="status-mini-count">{{ $cancelledCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        @php
            $total = $facultiesCount + $programmesCount + $nominationsCount;

            $facultiesPercent = $total > 0 ? round(($facultiesCount / $total) * 100, 2) : 0;
            $programmesPercent = $total > 0 ? round(($programmesCount / $total) * 100, 2) : 0;
            $nominationsPercent = $total > 0 ? round(($nominationsCount / $total) * 100, 2) : 0;

            $facultiesDeg = $facultiesPercent * 3.6;
            $programmesDeg = $facultiesDeg + $programmesPercent * 3.6;
        @endphp

        {{-- Donut Chart + Summary Row --}}
        <div class="row g-3 mb-4">

            {{-- Donut Chart --}}
            <div class="col-md-5">
                <div class="chart-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-chart-pie me-2 text-primary"></i>
                        <h6 class="mb-0 fw-bold">Overview Distribution</h6>
                        <span class="ms-auto text-muted small">{{ now()->format('Y') }}</span>
                    </div>

                    <div class="pie-wrapper">
                        <div class="pie-chart" id="overviewPie" data-faculties-end="{{ $facultiesDeg }}"
                            data-faculties-percent="{{ $facultiesPercent }}" data-programmes-end="{{ $programmesDeg }}"
                            data-programmes-percent="{{ $programmesPercent }}"
                            data-nominations-percent="{{ $nominationsPercent }}" style="background: conic-gradient(
                                                #0d6efd 0deg {{ $facultiesDeg }}deg,
                                                #198754 {{ $facultiesDeg }}deg {{ $programmesDeg }}deg,
                                                #ffc107 {{ $programmesDeg }}deg 360deg
                                            );">
                            <div class="pie-center"></div>
                            <div class="pie-tooltip" id="pieTooltip"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="legend-row">
                            <span><span class="legend-dot" style="background:#0d6efd;"></span> Faculties</span>
                            <span class="fw-bold">{{ $facultiesPercent }}%</span>
                            <span class="text-muted">{{ $facultiesCount }}</span>
                        </div>
                        <div class="legend-row">
                            <span><span class="legend-dot" style="background:#198754;"></span> Programmes</span>
                            <span class="fw-bold">{{ $programmesPercent }}%</span>
                            <span class="text-muted">{{ $programmesCount }}</span>
                        </div>
                        <div class="legend-row">
                            <span><span class="legend-dot" style="background:#ffc107;"></span> Nominations</span>
                            <span class="fw-bold">{{ $nominationsPercent }}%</span>
                            <span class="text-muted">{{ $nominationsCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="col-md-7">
                <div class="chart-card h-100">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fa fa-list-ul me-2 text-primary"></i>
                        <h6 class="mb-0 fw-bold">Summary</h6>
                    </div>

                    <div class="summary-row">
                        <div class="summary-icon bg-primary-soft text-primary"><i class="fa fa-chalkboard-teacher"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">Faculties</span>
                                <span class="fw-bold">{{ $facultiesCount }}</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill bg-primary" style="width: {{ $facultiesPercent }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-icon bg-success-soft text-success"><i class="fa fa-book"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">Programmes</span>
                                <span class="fw-bold">{{ $programmesCount }}</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill bg-success" style="width: {{ $programmesPercent }}%;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="summary-row">
                        <div class="summary-icon bg-warning-soft text-warning"><i class="fa fa-award"></i></div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">Nominations</span>
                                <span class="fw-bold">{{ $nominationsCount }}</span>
                            </div>

                            <div class="progress-track">
                                <div class="progress-fill bg-warning" style="width: {{ $nominationsPercent }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>



        <div class="row g-3">

            {{-- Nominations by Programme --}}
            <div class="col-md-6">
                <div class="chart-card h-100">
                    <div class="d-flex align-items-center mb-1">
                        <i class="fa fa-chart-bar me-2 text-primary"></i>
                        <h6 class="mb-0 fw-bold">Nominations by Programme</h6>
                        <span class="ms-auto text-muted small">Top {{ $programmeNominations->count() }} programmes</span>
                    </div>
                    <p class="text-muted small mb-3">Number of nominations received per programme</p>

                    @if ($programmeNominations->count() > 0)
                        @php
                            $maxNomCount = $programmeNominations->max('total') ?: 1;
                        @endphp

                        <div class="prog-bar-list">
                            @foreach ($programmeNominations as $item)
                                <div class="status-row">
                                    <span class="status-label" title="{{ $item->programme->title ?? 'N/A' }}">
                                        {{ Str::limit($item->programme->title ?? 'N/A', 28) }}
                                    </span>
                                    <div class="status-track">
                                        <div class="status-fill"
                                            style="width: {{ ($item->total / $maxNomCount) * 100 }}%; background: linear-gradient(90deg, #0d6efd, #4361ee);">
                                        </div>
                                    </div>
                                    <span class="status-count">{{ $item->total }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted small mb-0">No nomination data available yet.</p>
                    @endif
                </div>
            </div>

            {{-- Nominations by State --}}
            @include('admin.map.india-state-map')

            {{-- Class-wise Students --}}

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="chart-card h-100">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-user-graduate me-2 text-primary"></i>
                            <h6 class="mb-0 fw-bold">Class-wise participants</h6>
                            <span class="ms-auto text-muted small">Total {{ $classStudents->sum('total') }}</span>
                        </div>

                        @if ($classStudents->where('total', '>', 0)->count() > 0)
                            <div style="height: 320px;">
                                <canvas id="classStudentsChart"></canvas>
                            </div>
                        @else
                            <p class="text-muted small mb-0">No student data available yet.</p>
                        @endif
                    </div>
                </div>

                {{-- Nominations by Agency --}}
                <div class="col-md-6">
                    <div class="chart-card h-100">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fa fa-building me-2 text-primary"></i>
                            <h6 class="mb-0 fw-bold">Nominations by Agency</h6>
                            <span class="ms-auto text-muted small">{{ $agencyNominations->count() }} agencies</span>
                        </div>
                        <p class="text-muted small mb-3">Number of nominations received per agency</p>

                        @if ($agencyNominations->count() > 0)
                            @php
                                $maxAgencyCount = $agencyNominations->max('total') ?: 1;
                            @endphp

                            <div class="prog-bar-list" style="max-height: 320px; overflow-y: auto;">
                                @foreach ($agencyNominations as $item)
                                    <div class="status-row">
                                        <span class="status-label" title="{{ $item['agency'] }}">
                                            {{ Str::limit($item['agency'], 28) }}
                                        </span>
                                        <div class="status-track">
                                            <div class="status-fill"
                                                style="width: {{ ($item['total'] / $maxAgencyCount) * 100 }}%; background: linear-gradient(90deg, #fd7e14, #ffb703);">
                                            </div>
                                        </div>
                                        <span class="status-count">{{ $item['total'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted small mb-0">No agency data available yet.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

    </div>

    <style>
        .greeting-banner {
            background: white;
            border-radius: 18px;
            padding: 28px 32px;
        }

        /* ---- Stat Cards ---- */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 5px 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
            border-bottom: 4px solid;
            min-height: 120px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(0, 0, 0, 0.1);
        }

        .border-primary {
            border-bottom-color: #0d6efd !important;
        }

        .border-success {
            border-bottom-color: #198754 !important;
        }

        .border-warning {
            border-bottom-color: #ffc107 !important;
        }

        .stat-icon {
            width: 45px;
            height: 45px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .bg-primary-soft {
            background: rgba(13, 110, 253, 0.12);
        }

        .bg-success-soft {
            background: rgba(25, 135, 84, 0.12);
        }

        .bg-warning-soft {
            background: rgba(255, 193, 7, 0.15);
        }

        .badge-tag {
            font-size: 11px;
            background: #f1f2f6;
            color: #6c757d;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 700;
        }

        .stat-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }

        /* ---- Chart Card ---- */
        .chart-card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #f0f0f0;
        }

        .pie-wrapper {
            display: flex;
            justify-content: center;
            padding: 10px 0;
        }

        .pie-chart {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
        }

        .pie-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            height: 60%;
            background: #fff;
            border-radius: 50%;
            pointer-events: none;
        }

        .pie-tooltip {
            position: absolute;
            display: none;
            background: rgba(30, 30, 30, 0.92);
            color: #fff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            pointer-events: none;
            transform: translate(-50%, -130%);
            z-index: 10;
        }

        .legend-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .legend-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f5f5f5;
            font-size: 14px;
        }

        .legend-row:last-child {
            border-bottom: none;
        }

        /* ---- Summary rows ---- */
        .summary-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-icon {
            width: 30px;
            height: 30px;
            min-width: 30px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .progress-track {
            height: 6px;
            background: #f0f0f0;
            border-radius: 6px;
            margin-top: 6px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 6px;
        }

        /* ---- Programme-wise Nomination ---- */
        .prog-bar-list {
            padding-top: 8px;
        }

        .status-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .status-row:last-child {
            margin-bottom: 0;
        }

        .status-label {
            font-size: 13px;
            color: #555;
            min-width: 220px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-track {
            flex-grow: 1;
            height: 12px;
            background: #f0f1f5;
            border-radius: 6px;
            overflow: hidden;
        }

        .status-fill {
            height: 100%;
            border-radius: 6px;
        }

        .status-count {
            font-size: 13px;
            font-weight: 700;
            min-width: 30px;
            text-align: right;
        }

        /* class-wise student count */
        .class-list {
            padding-top: 4px;
        }

        .class-list-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f5f5f5;
        }

        .class-list-row:last-child {
            border-bottom: none;
        }

        .class-list-label {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }

        .class-list-count {
            background: #f1f2f6;
            color: #333;
            font-size: 13px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 8px;
            min-width: 28px;
            text-align: center;
        }

        .bg-info-soft {
            background: rgba(13, 202, 240, 0.12);
        }

        .border-info {
            border-bottom-color: #0dcaf0 !important;
        }

        /* ---- Status Summary Card ---- */
        .status-summary-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: nowrap;
        }

        .status-summary-title {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .status-mini-group {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 1px;
            flex-shrink: 0;
        }

        .status-mini-row {
            display: flex;
            align-items: center;
            /* gap: 2px; */
            background: #f8f9fa;
            border-radius: 8px;
            padding: 4px 6px;
            font-size: 8px;
            white-space: nowrap;
        }

        .status-mini-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .status-mini-label {
            color: #555;
            font-weight: 500;
        }

        .status-mini-count {
            font-weight: 700;
            color: #222;
            margin-left: 2px;
        }
    </style>

    <script src="{{ asset('assets/js/chart.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ---------- Overview Donut Tooltip ---------- */
            const pie = document.getElementById('overviewPie');
            const pieTooltip = document.getElementById('pieTooltip');

            if (pie && pieTooltip) {
                const facultiesEnd = parseFloat(pie.dataset.facultiesEnd);
                const facultiesPercent = pie.dataset.facultiesPercent;
                const programmesEnd = parseFloat(pie.dataset.programmesEnd);
                const programmesPercent = pie.dataset.programmesPercent;
                const nominationsPercent = pie.dataset.nominationsPercent;

                pie.addEventListener('mousemove', function (e) {
                    const rect = pie.getBoundingClientRect();
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const x = e.clientX - rect.left - centerX;
                    const y = e.clientY - rect.top - centerY;

                    let angle = Math.atan2(x, -y) * (180 / Math.PI);
                    if (angle < 0) angle += 360;

                    let label, percent, color;

                    if (angle <= facultiesEnd) {
                        label = 'Faculties';
                        percent = facultiesPercent;
                        color = '#0d6efd';
                    } else if (angle <= programmesEnd) {
                        label = 'Programmes';
                        percent = programmesPercent;
                        color = '#198754';
                    } else {
                        label = 'Nominations';
                        percent = nominationsPercent;
                        color = '#ffc107';
                    }

                    pieTooltip.style.display = 'block';
                    pieTooltip.style.left = (e.clientX - rect.left) + 'px';
                    pieTooltip.style.top = (e.clientY - rect.top) + 'px';
                    pieTooltip.style.borderLeft = '4px solid ' + color;
                    pieTooltip.innerHTML = label + ': <b>' + percent + '%</b>';
                });

                pie.addEventListener('mouseleave', function () {
                    pieTooltip.style.display = 'none';
                });
            }

            /* ---------- Nominations by State (Area + Trend Chart) ---------- */
            const stateCanvas = document.getElementById('stateBarChart');
            if (stateCanvas) {
                const ctx = stateCanvas.getContext('2d');

                const stateData = @json($stateNominations);
                const labels = stateData.map(item => item.state);
                const values = stateData.map(item => item.total);

                const gradient = ctx.createLinearGradient(0, 0, 0, 380);
                gradient.addColorStop(0, 'rgba(34, 197, 94, 0.4)');
                gradient.addColorStop(1, 'rgba(13, 110, 253, 0.15)');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Nominations',
                            data: values,
                            fill: true,
                            backgroundColor: gradient,
                            borderColor: '#22c55e',
                            borderWidth: 3,
                            tension: 0.4,
                            pointRadius: 0,
                            order: 2
                        },
                        {
                            label: 'Trend',
                            data: values,
                            borderColor: '#fd7e14',
                            borderWidth: 2,
                            pointBackgroundColor: '#fd7e14',
                            pointRadius: 5,
                            fill: false,
                            tension: 0,
                            order: 1
                        }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function (ctx) {
                                        return ctx.dataset.label + ': ' + ctx.parsed.y + ' nominations';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#f0f0f0'
                                },
                                ticks: {
                                    precision: 0
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('classStudentsChart');
            if (!canvas) return;

            const classData = @json($classStudents);
            const labels = classData.map(item => item.name);
            const values = classData.map(item => item.total);

            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Students',
                        data: values,
                        backgroundColor: '#4d5b9e',
                        borderRadius: 6,
                        categoryPercentage: 0.6,
                        barPercentage: 0.7
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
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            },
                            grid: {
                                color: '#f0f0f0'
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection