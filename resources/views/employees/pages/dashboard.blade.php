@extends('employees.layouts.app')

@section('title','Dashboard')

@section('content')

<div class="main-content">

    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <h6 class="text-muted">Total Employees</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0">245</h2>
                    <i class="fas fa-users fs-4 text-primary"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <h6 class="text-muted">Active Now</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0">189</h2>
                    <i class="fas fa-user-check fs-4 text-success"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <h6 class="text-muted">On Leave</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0">32</h2>
                    <i class="fas fa-bed fs-4 text-warning"></i>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="stat-card">
                <h6 class="text-muted">Open Roles</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0">15</h2>
                    <i class="fas fa-briefcase fs-4 text-danger"></i>
                </div>
            </div>
        </div>
    </div>
    <!-- Employee Table -->
    <div class="card border-0 shadow">
        <div class="card-body">
            <h5 class="card-title mb-4">Recent Employees</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Booking Number</th>
                            <th>Vehicle Number</th>
                            <th>Vehicle Model</th>
                            <th>Customer Name</th>
                            <th>Contact Number</th>
                            <th>Customer Complaint</th>
                            <th>Delivery Date</th>
                            <th>Service Status</th>
                            <th>Photos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($assignedServices as $service)
                        <tr>
                            <td>{{ $service->booking_id }}</td>
                            <td>{{ $service->vehicle_number }}</td>
                            <td>{{ $service->vehicle_model }}</td>
                            <td>{{ $service->customer_name }}</td>
                            <td>{{ $service->contact_number_1 }}</td>
                            <td>{{ $service->customer_complaint }}</td>
                            <td>{{ $service->expected_delivery_date }}</td>
                            <td>{{ $service->service_status }}</td>
                            <td>
                                @if (!empty($service->photos) && is_string($service->photos))
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach (json_decode($service->photos, true) as $photo)
                                        <img src="{{ asset('storage/' . $photo) }}" 
                                             class="img-thumbnail thumbnail-img" 
                                             data-bs-toggle="modal" 
                                             data-bs-target="#imageModal"
                                             onclick="updateModalImage('{{ asset('storage/' . $photo) }}')">
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">No Photos</span>
                                @endif
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