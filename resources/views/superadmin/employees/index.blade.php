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

    <div class="modal fade" id="employeeServicesModal" tabindex="-1" aria-labelledby="employeeServicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title">Employee Services</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Loading indicator -->
                    <div id="loadingIndicator" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading services...</p>
                    </div>

                    <!-- Services Table (initially hidden) -->
                    <div id="servicesContainer" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0 shadow-sm">
                                <thead class="table-light">
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Employee Remarks</th>
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
                    <!-- Close table button (initially hidden) -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close Modal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
            {{ $employees->links() }}
        </div>
    </div>
</div>
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
                    $('#servicesList').html('<tr><td colspan="5" class="text-center">No services available for this employee.</td></tr>');
                    $('#servicesContainer').show();
                    $('#closeTableBtn').hide(); // No need close button if empty
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
