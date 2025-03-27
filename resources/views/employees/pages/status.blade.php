@extends('employees.layouts.app')

@section('title','Dashboard')

@section('content')

<div class="main-content">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

    <!-- Employee Table -->
    <div class="main-content">
        <div class="card border-0 shadow-lg">
            <div class="card-body">
                <h5 class="card-title mb-4 text-primary">Assigned Services</h5>
                <div class="table-responsive">
                    <table class="service-table table">
                        <thead class="rounded-top">
                            <tr>
                                <th>Booking #</th>
                                <th>Vehicle</th>
                                <th>Model</th>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Complaint</th>
                                <th>Delivery Date</th>
                                <th>Status</th>
                                <th>Notes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedServices as $service)
                            <tr>
                                <td class="fw-500">#{{ $service->booking_id }}</td>
                                <td>{{ $service->vehicle_number }}</td>
                                <td>{{ $service->vehicle_model }}</td>
                                <td>{{ $service->customer_name }}</td>
                                <td>{{ $service->contact_number_1 }}</td>
                                <td class="text-truncate" style="max-width: 200px;">{{ $service->customer_complaint }}</td>
                                <td>{{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('d M Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('employee.updateStatus', $service->id) }}">
                                        @csrf
                                        <select name="status" class="form-select status-select border-2 rounded-3 py-1 px-2 shadow-sm" style="width: 130px;">
                                            <option value="" selected disabled>Select Status</option> <!-- Default option -->
                                            <option value="accepted" {{ $service->service_status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="in_progress" {{ $service->service_status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ $service->service_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="rejected" {{ $service->service_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                </td>
                                <td>
                                        <textarea name="notes" class="notes-textarea w-100">{{ $service->notes }}</textarea>
                                </td>
                                <td>
                                        <button type="submit" class="btn btn-primary update-btn">
                                            <i class="fas fa-sync-alt me-1"></i>Update
                                        </button>
                                    </form> <!-- Move the closing form tag here to wrap all form inputs -->
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
