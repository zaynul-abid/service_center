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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Recent Services</h5>
                    <div class="d-flex gap-2">
                        <input type="search" class="form-control form-control-sm" placeholder="Search..." id="searchInput">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover service-table">
                        <thead class="table-primary">
                        <tr>
                            <th>Booking #</th>
                            <th>Vehicle</th>
                            <th class="d-none d-md-table-cell">Model</th>
                            <th class="d-none d-lg-table-cell">Customer</th>
                            <th class="d-none d-xl-table-cell">Contact</th>
                            <th class="d-none d-xl-table-cell">Complaint</th>
                            <th>Delivery</th>
                            <th>Status</th>
                            <th>Photos</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($assignedServices as $service)
                            <tr>
                                <td>{{ $service->booking_id }}</td>
                                <td>
                                    <span class="d-block">{{ $service->vehicle_number }}</span>
                                    <small class="text-muted d-md-none">{{ $service->vehicle_model }}</small>
                                </td>
                                <td class="d-none d-md-table-cell">{{ $service->vehicle_model }}</td>
                                <td class="d-none d-lg-table-cell">{{ $service->customer_name }}</td>
                                <td class="d-none d-xl-table-cell">{{ $service->contact_number_1 }}</td>
                                <td class="d-none d-xl-table-cell">
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $service->customer_complaint }}">
                                        {{ $service->customer_complaint }}
                                    </div>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('M d') }}
                                </td>
                                <td>
                                        <span class="status-badge status-{{ strtolower(str_replace(' ', '_', $service->service_status)) }}">
                                            {{ $service->service_status }}
                                        </span>
                                </td>
                                <td>
                                    @if (!empty($service->photos) && is_string($service->photos))
                                        <div class="d-flex gap-2 flex-wrap">
                                            @foreach (json_decode($service->photos, true) as $photo)
                                                <img src="{{ asset('storage/' . $photo) }}"
                                                     class="img-thumbnail thumbnail-img"
                                                     data-bs-toggle="modal"
                                                     data-bs-target="#imageModal"
                                                     onclick="updateModalImage('{{ asset('storage/' . $photo) }}')"
                                                     alt="Service photo">
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

                @if($assignedServices->isEmpty())
                    <div class="alert alert-info mt-3">
                        No services assigned to you yet.
                    </div>
                @endif


            </div>
        </div>
    </div>
@endsection
