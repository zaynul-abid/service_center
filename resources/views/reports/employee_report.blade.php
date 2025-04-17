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
                <div class="d-flex align-items-center">
                    <span class="badge bg-primary me-3">
                        Total Employees: {{ $employees->total() }}
                    </span>
                    <span class="badge bg-success me-3">
                        Active Assignments: {{ $employees->sum('assigned_services_count') }}
                    </span>
                    <a href="{{ route('report.employee.download') }}" class="btn btn-primary">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                </div>
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
                            <th class="px-2 text-center">Assigned</th>
                            <th class="pe-3">Services</th>
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
                                <td class="px-2 text-center">
                                    <span class="badge {{ $employee->assigned_services_count > 0 ? 'bg-success' : 'bg-light text-danger border' }}">
                                        {{ $employee->assigned_services_count }}
                                    </span>
                                </td>
                                <td class="pe-3">
                                    @if($employee->services->isNotEmpty())
                                        <button class="btn btn-sm btn-outline-primary view-services-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#servicesModal"
                                                data-employee="{{ $employee->name }}"
                                                data-services="{{ json_encode($employee->services->map(function($service) {
                                                    return [
                                                        'booking_id' => $service->booking_id,
                                                        'created_at' => $service->created_at->format('M d'),
                                                        'employee_remarks' => $service->employee_remarks
                                                    ];
                                                })->toArray()) }}">
                                            <i class="fas fa-eye me-1"></i> View Services
                                        </button>
                                    @else
                                        <span class="badge bg-light text-danger border border-danger">
                                            <i class="fas fa-exclamation-circle me-1"></i> No Services
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
                        {{ $employees->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Services Modal -->
    <div class="modal fade" id="servicesModal" tabindex="-1" aria-labelledby="servicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-gradient-primary text-white">
                    <h5 class="modal-title" id="servicesModalLabel">Services for <span id="employeeName"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div id="servicesList"></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>
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
        .service-item {
            border-left: 4px solid var(--bs-primary);
            border-radius: 0.375rem;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .service-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .remarks {
            font-size: 0.85rem;
            line-height: 1.4;
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
        }

        /* Modal Styles */
        .modal-dialog {
            max-width: 700px;
        }
        .modal-content {
            border-radius: 1rem;
            overflow: hidden;
            animation: slideIn 0.3s ease-out;
        }
        .bg-gradient-primary {
            background: linear-gradient(45deg, #007bff, #00aaff);
        }
        .modal-header {
            padding: 1.5rem;
        }
        .modal-title {
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .btn-close-white {
            filter: invert(1);
        }
        .modal-body {
            background: #f8f9fa;
            max-height: 60vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #adb5bd #f8f9fa;
        }
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }
        .modal-body::-webkit-scrollbar-track {
            background: #f8f9fa;
        }
        .modal-body::-webkit-scrollbar-thumb {
            background-color: #adb5bd;
            border-radius: 4px;
        }
        .modal-footer {
            padding: 1rem 1.5rem;
            background: #fff;
        }
        .btn-outline-secondary {
            border-radius: 0.375rem;
            padding: 0.5rem 1.5rem;
            transition: all 0.2s ease;
        }
        .btn-outline-secondary:hover {
            background: #f1f3f5;
        }
        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
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

            // Handle modal content population
            const servicesModal = document.getElementById('servicesModal');
            servicesModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const employeeName = button.getAttribute('data-employee');
                const services = JSON.parse(button.getAttribute('data-services'));

                // Update modal title
                document.getElementById('employeeName').textContent = employeeName;

                // Populate services list
                const servicesList = document.getElementById('servicesList');
                servicesList.innerHTML = '';

                services.forEach(service => {
                    const serviceItem = document.createElement('div');
                    serviceItem.className = 'service-item bg-white rounded p-3 mb-3';
                    serviceItem.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary me-2">#${service.booking_id}</span>
                            <small class="text-muted">${service.created_at}</small>
                        </div>
                        <div class="remarks">
                            ${service.employee_remarks ?
                        `<p class="m-0 text-dark"><i class="fas fa-comment-alt text-info me-2"></i>${service.employee_remarks}</p>` :
                        `<p class="m-0 text-muted"><i class="fas fa-comment-alt text-secondary me-2"></i>No remarks</p>`
                    }
                        </div>
                    `;
                    servicesList.appendChild(serviceItem);
                });
            });

            // Run initially and on window resize
            makeTableResponsive();
            window.addEventListener('resize', makeTableResponsive);
        });
    </script>
@endsection
