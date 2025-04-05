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
                    <i class="fas fa-users text-primary me-2"></i>Employee Report with Services
                </h4>
                <a href="{{ route('report.employee.download') }}" class="btn btn-primary">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
            </div>

            <div class="card-body p-3 p-md-4">
                <div class="table-responsive rounded-3 border">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th width="50" class="ps-3">#</th>
                            <th class="px-2">Name</th>
                            <th class="px-2">Designation</th>
                            <th class="px-2">Phone</th>
                            <th class="px-2">Email</th>
                            <th class="px-2">Department</th>
                            <th class="pe-3">Assigned Services</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $employee)
                            <tr class="border-top">
                                <td class="ps-3">{{ $loop->iteration }}</td>
                                <td class="px-2 fw-medium">{{ $employee->name }}</td>
                                <td class="px-2">{{ $employee->position }}</td>
                                <td class="px-2">{{ $employee->phone }}</td>
                                <td class="px-2">
                                    <div class="text-truncate" style="max-width: 200px;" data-bs-toggle="tooltip" title="{{ $employee->email }}">
                                        {{ $employee->email }}
                                    </div>
                                </td>
                                <td class="px-2">
                                    @if($employee->department)
                                        <span class="badge bg-light text-dark py-1 px-2">
                                            {{ $employee->department->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary py-1 px-2">N/A</span>
                                    @endif
                                </td>
                                <td class="pe-3">
                                    @if($employee->services->isNotEmpty())
                                        <div class="service-container" style="max-height: 150px; overflow-y: auto;">
                                            @foreach($employee->services as $service)
                                                <div class="service-item bg-light rounded p-2 mb-2">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-primary me-2">
                                                            #{{ $service->booking_id }}
                                                        </span>
                                                        <small class="text-muted">
                                                            {{ $service->created_at->format('M d') }}
                                                        </small>
                                                    </div>
                                                    <div class="remarks mt-1">
                                                        @if($service->employee_remarks)
                                                            <p class="m-0 small text-dark">
                                                                <i class="fas fa-comment-alt text-info me-1"></i>
                                                                {{ Str::limit($service->employee_remarks, 50) }}
                                                            </p>
                                                        @else
                                                            <p class="m-0 small text-muted">
                                                                <i class="fas fa-comment-alt text-secondary me-1"></i>
                                                                No remarks
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="badge bg-light text-danger border border-danger">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            No Services
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                @if($employees->isEmpty())
                    <div class="alert alert-info mt-4 mx-2">
                        <i class="fas fa-info-circle me-2"></i> No employee records found
                    </div>
                @endif

                @if($employees->hasPages())
                    <div class="mt-4 px-2">
                        {{ $employees->links() }}
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
        .service-container {
            scrollbar-width: thin;
            scrollbar-color: #dee2e6 #f8f9fa;
        }
        .service-container::-webkit-scrollbar {
            width: 6px;
        }
        .service-container::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
        .service-container::-webkit-scrollbar-thumb {
            background-color: #dee2e6;
            border-radius: 3px;
        }
        .service-item {
            border-left: 3px solid var(--bs-primary);
        }
        .remarks {
            font-size: 0.8rem;
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
                flex-direction: column;
                padding: 0.5rem;
                border-bottom: 1px solid #f0f0f0;
            }
            .table td:before {
                content: attr(data-label);
                font-weight: 600;
                margin-bottom: 0.25rem;
                color: #6c757d;
                text-transform: uppercase;
                font-size: 0.7rem;
            }
            .table td:last-child {
                border-bottom: 0;
            }
            .service-container {
                max-height: none !important;
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
