@extends('employees.layouts.app')

@section('title', 'Dashboard')

@section('content')

    <div class="main-content">

        {{-- Display Success and Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



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
