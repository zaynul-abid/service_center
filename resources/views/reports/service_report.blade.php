@extends('backend.layouts.app')

@if(auth()->user()->usertype === 'founder')
    @section('navbar')
        @include('founder.partials.navbar')
    @endsection
@elseif(auth()->user()->usertype === 'superadmin')
    @section('navbar')
        @include('superadmin.partials.navbar')
    @endsection
@elseif(auth()->user()->usertype === 'admin')
    @section('navbar')
        @include('admin.partials.navbar')
    @endsection
@endif

@section('content')
    <div class="container-fluid px-3 px-lg-4 py-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h4 class="mb-3 mb-md-0">
                    <i class="fas fa-file-alt text-primary me-2"></i>Service Report
                </h4>
                <a href="{{ route('cost.report.download') }}" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
            </div>

            <div class="card-body p-3 p-md-4">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th width="50" class="ps-3">#</th>
                            <th class="px-2">Booking No.</th>
                            <th class="px-2">Date</th>
                            <th class="px-2">Customer</th>
                            <th class="px-2">Vehicle</th>
                            <th class="px-2">Model</th>
                            <th class="px-2">Contact</th>
                            <th class="px-2">Service</th>
                            <th class="px-2">Cost</th>
                            <th class="px-2">Employee</th>
                            <th class="px-2">Status</th>
                            <th class="pe-3">Delivery</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($services as $service)
                            <tr class="border-top">
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td class="px-2 fw-medium">{{ $service->booking_id }}</td>
                                <td class="px-2">{{ \Carbon\Carbon::parse($service->booking_date)->format('d/m/Y') }}</td>
                                <td class="px-2">{{ $service->customer_name }}</td>
                                <td class="px-2">{{ $service->vehicle_number }}</td>
                                <td class="px-2">{{ $service->vehicle_model }}</td>
                                <td class="px-2">{{ $service->contact_number_1 }}</td>
                                <td class="px-2">
                                    <div class="text-truncate" style="max-width: 150px;" data-bs-toggle="tooltip" title="{{ $service->service_details }}">
                                        {{ $service->service_details }}
                                    </div>
                                </td>
                                <td class="px-2 text-nowrap">₹{{ number_format($service->cost, 2) }}</td>
                                <td class="px-2">
                                    @if($service->employee)
                                        <span class="badge bg-light text-dark py-1 px-2">
                                    {{ $service->employee->name }}
                                </span>
                                    @else
                                        <span class="badge bg-secondary py-1 px-2">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="px-2">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-warning',
                                            'in_progress' => 'bg-info',
                                            'completed' => 'bg-success',
                                            'delivered' => 'bg-primary',
                                            'cancelled' => 'bg-danger'
                                        ][$service->service_status] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-capitalize py-1 px-2">
                                    {{ str_replace('_', ' ', $service->service_status) }}
                                </span>
                                </td>
                                <td class="pe-3">{{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($services->isEmpty())
                    <div class="alert alert-info mt-4 mx-2">
                        <i class="fas fa-info-circle me-2"></i> No service records found
                    </div>
                @endif

                @if($services->hasPages())
                    <div class="mt-4 px-2">
                        {{ $services->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .card {
            border-radius: 0.75rem;
        }
        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(0, 0, 0, 0.02);
            font-size: 0.875rem;
        }
        .table thead th {
            white-space: nowrap;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
            vertical-align: middle;
        }
        .table td {
            vertical-align: middle;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        .table tr:first-child {
            border-top: none;
        }
        .badge {
            font-weight: 500;
        }
        @media (max-width: 991.98px) {
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
        @media (max-width: 767.98px) {
            .card-body {
                padding: 1rem;
            }
            .table-responsive {
                border: 0;
            }
            .table thead {
                display: none;
            }
            .table tr {
                display: block;
                margin-bottom: 1.25rem;
                border: 1px solid #dee2e6;
                border-radius: 0.5rem;
                padding: 0.5rem;
            }
            .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.5rem;
                border-bottom: 1px solid #f0f0f0;
            }
            .table td:before {
                content: attr(data-label);
                font-weight: 600;
                margin-right: 1rem;
                color: #6c757d;
                text-transform: uppercase;
                font-size: 0.7rem;
                flex: 0 0 120px;
            }
            .table td:last-child {
                border-bottom: 0;
            }
            .badge {
                font-size: 0.7rem;
                padding: 0.35em 0.65em;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Make table responsive by adding data-labels
            function makeTableResponsive() {
                if (window.innerWidth < 768) {
                    document.querySelectorAll('.table thead th').forEach((th, index) => {
                        const label = th.textContent.trim();
                        document.querySelectorAll(`.table tbody td:nth-child(${index + 1})`).forEach(td => {
                            td.setAttribute('data-label', label);
                        });
                    });
                }
            }

            // Run initially and on window resize
            makeTableResponsive();
            window.addEventListener('resize', makeTableResponsive);
        });
    </script>
@endsection
