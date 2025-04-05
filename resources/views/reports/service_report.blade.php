@extends('backend.layouts.app')
@section('title', 'Service Analytics')
@section('navbar')
    @includeWhen(auth()->user()->usertype === 'founder', 'founder.partials.navbar')
    @includeWhen(auth()->user()->usertype === 'superadmin', 'superadmin.partials.navbar')
    @includeWhen(auth()->user()->usertype === 'admin', 'admin.partials.navbar')
@endsection

@section('content')
    <div class="container-fluid px-0 px-md-3">
        <div class="report-container bg-white rounded-4 shadow-sm p-3 p-md-4 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 mb-md-4 gap-2">
                <div class="mb-2 mb-md-0">
                    <h1 class="h3 fw-semibold mb-1">Service Analytics</h1>
                    <p class="text-muted mb-0">Detailed breakdown of service requests and status metrics</p>
                </div>
                <div class="d-flex gap-2 w-100 w-md-auto">
                    <a href="{{ route('report.service.download', request()->all()) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 flex-grow-1 flex-md-grow-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i> <span class="d-none d-md-inline">Export</span> Report
                    </a>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="filter-card bg-light rounded-3 p-3 mb-4">
                <form method="GET" action="{{ route('admin.service.report') }}" id="filterForm">
                    <div class="row g-2 g-md-3 align-items-end">
                        <div class="col-12 col-md-4">
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
                        <div class="col-12 col-md-4">
                            <label class="form-label small text-uppercase fw-semibold text-muted">Status</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-dark rounded-pill flex-grow-1">
                                <i class="bi bi-funnel me-1"></i> <span class="d-none d-md-inline">Apply</span> Filters
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" id="resetFilter">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Summary Cards -->
            <div class="row g-2 g-md-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="stat-card bg-white rounded-3 p-2 p-md-3 border h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase small text-muted fw-semibold mb-1">Total Services</h6>
                                <h3 class="mb-0">{{ number_format($totalServices) }}</h3>
                            </div>
                            <div class="icon-container bg-light rounded-circle p-2">
                                <i class="bi bi-tools text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-card bg-white rounded-3 p-2 p-md-3 border h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase small text-muted fw-semibold mb-1">Pending</h6>
                                <h3 class="mb-0">{{ number_format($pendingServices) }}</h3>
                            </div>
                            <div class="icon-container bg-light rounded-circle p-2">
                                <i class="bi bi-hourglass-top text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-card bg-white rounded-3 p-2 p-md-3 border h-100">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="text-uppercase small text-muted fw-semibold mb-1">Completed</h6>
                                <h3 class="mb-0">{{ number_format($completedServices) }}</h3>
                            </div>
                            <div class="icon-container bg-light rounded-circle p-2">
                                <i class="bi bi-check-circle text-dark"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Services Table - Dual View for Desktop/Mobile -->
            <div class="table-card bg-white rounded-3 overflow-hidden border">
                <!-- Desktop Table View with Proper Alignment -->
                <div class="d-none d-md-block">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th class="text-uppercase small text-muted fw-semibold py-3 ps-4" style="width: 5%">#</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3" style="width: 10%">Booking</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3" style="width: 10%">Date</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3" style="width: 15%">Customer</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3" style="width: 15%">Vehicle</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3" style="width: 25%">Service</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3 text-end" style="width: 10%">Amount</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3 pe-4" style="width: 10%">Status</th>
                                <th class="text-uppercase small text-muted fw-semibold py-3 pe-4" style="width: 10%">Employee Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($services as $service)
                                <tr class="border-top">
                                    <td class="ps-4 align-top">{{ $loop->iteration }}</td>
                                    <td class="align-top">
                                        <span class="d-inline-block bg-light rounded-pill px-2 py-1 small">
                                            {{ $service->booking_id }}
                                        </span>
                                    </td>
                                    <td class="align-top">{{ \Carbon\Carbon::parse($service->booking_date)->format('M d, Y') }}</td>
                                    <td class="align-top">
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $service->customer_name }}</span>
                                            <small class="text-muted">{{ $service->contact_number_1 }}</small>
                                        </div>
                                    </td>
                                    <td class="align-top">
                                        <div class="d-flex flex-column">
                                            <span>{{ $service->vehicle_number }}</span>
                                            <small class="text-muted">{{ $service->vehicle_model }}</small>
                                        </div>
                                    </td>
                                    <td class="align-top">
                                        <div class="service-details">
                                            {{ $service->service_details }}
                                        </div>
                                    </td>
                                    <td class="text-end fw-medium align-top">₹{{ number_format($service->cost, 2) }}</td>
                                    <td class="pe-4 align-top">
                                        @php
                                            $statusClass = [
                                                 'pending' => 'bg-warning-subtle text-warning',
                                                 'completed' => 'bg-success-subtle text-success',
                                                 'in_progress' => 'bg-info-subtle text-info'
                                             ][$service->status] ?? 'bg-light text-dark';
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClass }} px-2 py-1">
                                            {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                        </span>
                                    </td>

                                    <td class="pe-4 align-top">
                                        @php
                                            $statusClass = [
                                                 'pending' => 'bg-warning-subtle text-warning',
                                                 'completed' => 'bg-success-subtle text-success',
                                                 'in_progress' => 'bg-info-subtle text-info'
                                             ][$service->service_status] ?? 'bg-light text-dark';
                                        @endphp
                                        <span class="badge rounded-pill {{ $statusClass }} px-2 py-1">
                                            {{ ucfirst(str_replace('_', ' ', $service->service_status)) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="d-md-none">
                    @foreach($services as $service)
                        @php
                            $statusClass = [
                                 'pending' => 'bg-warning-subtle text-warning',
                                 'completed' => 'bg-success-subtle text-success',
                                 'in_progress' => 'bg-info-subtle text-info'
                             ][$service->status] ?? 'bg-light text-dark';
                        @endphp

                        <div class="service-card border-bottom p-3">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <span class="badge {{ $statusClass }} rounded-pill px-2 py-1 mb-1">
                                        {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                    </span>
                                    <h6 class="mb-1 fw-semibold">Booking #{{ $service->booking_id }}</h6>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($service->booking_date)->format('M d, Y') }}</small>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold">₹{{ number_format($service->cost, 2) }}</div>
                                    <small class="text-muted">Amount</small>
                                </div>
                            </div>

                            <div class="service-details mb-2">
                                <small class="text-muted">Service:</small>
                                <div class="text-break">{{ $service->service_details }}</div>
                            </div>

                            <div class="row g-2">
                                <div class="col-6">
                                    <small class="text-muted">Customer:</small>
                                    <div class="fw-medium">{{ $service->customer_name }}</div>
                                    <small>{{ $service->contact_number_1 }}</small>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Vehicle:</small>
                                    <div class="fw-medium">{{ $service->vehicle_number }}</div>
                                    <small>{{ $service->vehicle_model }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pagination -->
            @if($services->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $services->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>

    <style>
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .icon-container {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .service-details {
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        @media (max-width: 767.98px) {
            .report-container {
                padding: 1rem !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reset filter
            document.getElementById('resetFilter').addEventListener('click', function() {
                document.getElementById('filterForm').reset();
                window.location.href = "{{ route('admin.service.report') }}";
            });

            // Date validation
            document.getElementById('filterForm').addEventListener('submit', function(e) {
                const startDate = document.getElementById('start_date').value;
                const endDate = document.getElementById('end_date').value;

                if ((startDate && !endDate) || (!startDate && endDate)) {
                    e.preventDefault();
                    alert('Please select both start and end dates or clear both');
                    return false;
                }

                if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
                    e.preventDefault();
                    alert('Start date cannot be after end date');
                    return false;
                }
            });
        });
    </script>
@endsection
