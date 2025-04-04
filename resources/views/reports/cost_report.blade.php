@extends('backend.layouts.app')

@section('navbar')
    @includeWhen(auth()->user()->usertype === 'founder', 'founder.partials.navbar')
    @includeWhen(auth()->user()->usertype === 'superadmin', 'superadmin.partials.navbar')
    @includeWhen(auth()->user()->usertype === 'admin', 'admin.partials.navbar')
@endsection

@section('content')
    <div class="container-fluid px-4">
        <div class="report-container bg-white rounded-4 shadow-sm p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-semibold mb-1">Service Cost Analytics</h1>
                    <p class="text-muted mb-0">Detailed breakdown of service costs and performance metrics</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('cost.report.download', request()->all()) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export Report
                    </a>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="filter-card bg-light rounded-3 p-3 mb-4">
                <form method="GET" action="{{ route('report.service.cost') }}" id="filterForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small text-uppercase fw-semibold text-muted">Date Range</label>
                            <div class="input-group">
                                <input type="date" name="start_date" id="start_date"
                                       class="form-control form-control-sm border-end-0 rounded-start"
                                       value="{{ request('start_date') }}">
                                <span class="input-group-text bg-white border-start-0 border-end-0">to</span>
                                <input type="date" name="end_date" id="end_date"
                                       class="form-control form-control-sm border-start-0 rounded-end"
                                       value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-uppercase fw-semibold text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-dark rounded-pill flex-grow-1">
                                <i class="bi bi-funnel me-1"></i> Apply Filters
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" id="resetFilter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stat-card bg-white rounded-3 p-3 border">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase small text-muted fw-semibold mb-1">Total Cost</h6>
                                <h3 class="mb-0">₹{{ number_format($totalServiceCost, 2) }}</h3>
                            </div>
                            <div class="icon-container bg-light rounded-circle p-2">
                                <i class="bi bi-cash-stack text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card bg-white rounded-3 p-3 border">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase small text-muted fw-semibold mb-1">Pending</h6>
                                <h3 class="mb-0">₹{{ number_format($pendingServiceCost, 2) }}</h3>
                            </div>
                            <div class="icon-container bg-light rounded-circle p-2">
                                <i class="bi bi-hourglass-top text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card bg-white rounded-3 p-3 border">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase small text-muted fw-semibold mb-1">Completed</h6>
                                <h3 class="mb-0">₹{{ number_format($completedServiceCost, 2) }}</h3>
                            </div>
                            <div class="icon-container bg-light rounded-circle p-2">
                                <i class="bi bi-check-circle text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Table -->
            <div class="table-card bg-white rounded-3 overflow-hidden border">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                        <tr>
                            <th class="text-uppercase small text-muted fw-semibold py-3 ps-4">#</th>
                            <th class="text-uppercase small text-muted fw-semibold py-3">Booking</th>
                            <th class="text-uppercase small text-muted fw-semibold py-3">Date</th>
                            <th class="text-uppercase small text-muted fw-semibold py-3">Customer</th>
                            <th class="text-uppercase small text-muted fw-semibold py-3">Vehicle</th>
                            <th class="text-uppercase small text-muted fw-semibold py-3">Service</th>
                            <th class="text-uppercase small text-muted fw-semibold py-3 text-end">Amount</th>
                            <th class="text-uppercase small text-muted fw-semibold py-3 pe-4">Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $service)
                            <tr class="border-top">
                                <td class="ps-4">{{ $loop->iteration }}</td>
                                <td>
                                <span class="d-inline-block bg-light rounded-pill px-2 py-1 small">
                                    {{ $service->booking_id }}
                                </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($service->booking_date)->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $service->customer_name }}</span>
                                        <small class="text-muted">{{ $service->contact_number_1 }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span>{{ $service->vehicle_number }}</span>
                                        <small class="text-muted">{{ $service->vehicle_model }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="service-details-truncate" style="max-width: 200px;">
                                        {{ $service->service_details }}
                                    </div>
                                </td>
                                <td class="text-end fw-medium">₹{{ number_format($service->cost, 2) }}</td>
                                <td class="pe-4">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-warning-subtle text-warning-emphasis',
                                            'completed' => 'bg-success-subtle text-success-emphasis',
                                            'in_progress' => 'bg-info-subtle text-info-emphasis'
                                        ][$service->status] ?? 'bg-light text-dark';
                                    @endphp
                                    <span class="badge rounded-pill {{ $statusClass }} px-2 py-1">
                                    {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('resetFilter').addEventListener('click', function() {
            document.getElementById('filterForm').reset();
            window.location.href = "{{ route('report.service.cost') }}";
        });
    </script>

    <style>
        .report-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .stat-card {
            transition: transform 0.2s;
        }
        .stat-card:hover {
            transform: translateY(-3px);
        }
        .icon-container {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .table-card {
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .service-details-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .rounded-4 {
            border-radius: 0.75rem !important;
        }
        .badge {
            font-weight: 500;
            letter-spacing: 0.4px;
        }
    </style>
@endsection
