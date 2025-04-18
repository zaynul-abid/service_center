@extends('backend.layouts.app')

@section('title', 'Employee List')


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





<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Employee List</h4>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Create Employee</a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif



    <div class="card-body">
        <table id="datatablesSimple" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Employee Status</th>


                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->phone ?? 'N/A' }}</td>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>

                            <td>
                                <span class="{{ empty($employee->position) ? 'text-danger' : '' }}">
                                    {{ $employee->position ?? 'N/A' }}
                                </span>
                            </td>




                            <td>
                                @if($employee->services->isNotEmpty())
                                    <button class="btn btn-sm btn-primary view-status-btn" data-id="{{ $employee->id }}">View</button>


                                @else
                                    <span class="text-danger">No Services Assigned</span>
                                @endif
                            </td>

                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">Edit</a>


                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach

                        @if ($employees->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center text-muted">No Employees found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

    <!-- Employee Services Modal -->
    <div class="modal fade" id="employeeServicesModal" tabindex="-1" aria-labelledby="employeeServicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="employeeServicesModalLabel">Employee Services</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading indicator -->
                    <div id="loadingIndicator" class="text-center py-3">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading services...</p>
                    </div>

                    <!-- Services Table (initially hidden) -->
                    <div id="servicesContainer" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Employee Remarks</th>
                                    <th>Technician Notes</th>
                                    <th>Vehicle Number</th>
                                    <th>Customer Name</th>
                                    <th>Service Status</th>
                                </tr>
                                </thead>
                                <tbody id="servicesList"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close Modal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
            {{ $employees->links() }}
        </div>
    </div>
</div>

<style>
    /* Modal styling */
    .modal-content {
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-dialog {
        max-width: 90%;
        margin: 1rem auto;
    }

    .modal-header {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 500;
    }

    .modal-body {
        padding: 1rem;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .modal-footer {
        padding: 0.75rem 1rem;
        border-top: 1px solid #dee2e6;
    }

    /* Table styling */
    .table {
        font-size: 0.8rem;
        margin-bottom: 0;
    }

    .table th,
    .table td {
        padding: 0.5rem;
        vertical-align: middle;
        border: 1px solid #dee2e6;
        white-space: nowrap; /* Prevent text wrapping */
    }

    .table th {
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.75rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .table-responsive {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 6px;
        overflow-x: auto; /* Ensure horizontal scrolling */
    }

    /* Loading indicator */
    .spinner-border {
        width: 1.25rem;
        height: 1.25rem;
    }

    #loadingIndicator p {
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    /* Buttons */
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        border-radius: 4px;
    }

    /* Mobile-specific adjustments */
    @media (max-width: 576px) {
        .modal-dialog {
            max-width: 100%;
            margin: 0;
            height: 100%;
        }

        .modal-content {
            border-radius: 0; /* Remove border radius for full-screen effect */
            box-shadow: none; /* Optional: remove shadow for cleaner mobile look */
            height: 100%;
        }

        .modal-header {
            padding: 0.5rem 0.75rem;
        }

        .modal-title {
            font-size: 0.9rem;
        }

        .modal-body {
            padding: 0.75rem;
            font-size: 0.8rem;
        }

        .modal-footer {
            padding: 0.5rem 0.75rem;
        }

        .table {
            font-size: 0.75rem;
        }

        .table th,
        .table td {
            padding: 0.4rem;
            font-size: 0.7rem;
        }

        .table-responsive {
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        }

        .btn-sm {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        #loadingIndicator .spinner-border {
            width: 1rem;
            height: 1rem;
        }

        #loadingIndicator p {
            font-size: 0.75rem;
        }
    }
</style>
<script>
    $(document).on('click', '.view-status-btn', function() {
        let employeeId = $(this).data('id');
        console.log('Clicked! Employee ID:', employeeId);

        // Show loading indicator
        $('#loadingIndicator').show();
        $('#servicesContainer').hide();
        $('#closeTableBtn').hide();

        // Use the Blade route() helper to generate the correct URL
        let url = '{{ route("employee.services", ":id") }}'.replace(':id', employeeId);

        // Make an AJAX request to fetch the services data
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                // Hide loading indicator
                $('#loadingIndicator').hide();

                // Clear previous content
                $('#servicesList').empty();

                if (response.length > 0) {
                    // Populate the table
                    response.forEach(function(service) {
                        let serviceRow = `
                <tr>
                    <td>${service.booking_id}</td>
                    <td>${service.employee_remarks || 'N/A'}</td>
                    <td>${service.technician_notes || 'N/A'}</td> <!-- Add Technician Notes field -->
                    <td>${service.vehicle_number}</td>
                    <td>${service.customer_name}</td>
                    <td><span class="badge ${getStatusClass(service.service_status)}">${service.service_status}</span></td>

                </tr>
            `;
                        $('#servicesList').append(serviceRow);
                    });

                    // Show the table and close button
                    $('#servicesContainer').show();
                    $('#closeTableBtn').show();
                } else {
                    // Show message if no services found
                    $('#servicesList').html('<tr><td colspan="6" class="text-center">No services available for this employee.</td></tr>'); <!-- Update colspan to 6 -->
                    $('#servicesContainer').show();
                    $('#closeTableBtn').hide(); // No need for close button if empty
                }

                // Show the modal
                $('#employeeServicesModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.log('Error fetching services:', error);
                $('#loadingIndicator').html('<p class="text-danger">Error loading services. Please try again.</p>');
                $('#closeTableBtn').hide();
            }
        });
    });

    // Close table button handler
    $('#closeTableBtn').on('click', function() {
        $('#servicesContainer').hide();
        $(this).hide();
        $('#loadingIndicator').show();
    });

    // Helper function for status badge styling
    function getStatusClass(status) {
        if (!status) return 'bg-secondary';

        status = status.toLowerCase();
        switch (status) {
            case 'completed': return 'bg-success';
            case 'in progress': return 'bg-primary';
            case 'pending': return 'bg-warning text-dark';
            case 'cancelled': return 'bg-danger';
            default: return 'bg-secondary';
        }
    }
</script>


@endsection
