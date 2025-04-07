@extends('employees.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Recent Services</h4>
            <div class="d-flex gap-2">
                <input type="search" class="form-control form-control-sm" placeholder="Search..." id="searchInput">
            </div>
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

        <div class="card-body">
            <div class="table-responsive">
                <table id="serviceTable" class="table table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>Booking #</th>
                        <th>Vehicle</th>
                        <th class="d-none d-md-table-cell">Model</th>
                        <th class="d-none d-lg-table-cell">Customer</th>
                        <th class="d-none d-xl-table-cell">Contact</th>
                        <th class="d-none d-xl-table-cell">Complaint</th>
                        <th>Delivery</th>
                        <th>Admin Status</th>
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
                                <div class="text-truncate" style="max-width: 200px;"
                                     title="{{ $service->customer_complaint }}">
                                    {{ $service->customer_complaint }}
                                </div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('M d') }}
                            </td>
                            <td>
                                    <span class="badge bg-{{
                                        $service->service_status == 'Completed' ? 'success' :
                                        ($service->service_status == 'In Progress' ? 'warning' :
                                        ($service->service_status == 'Pending' ? 'secondary' : 'primary'))
                                    }}">
                                        {{ $service->service_status }}
                                    </span>
                            </td>
                            <td>
                                    <span class="badge bg-{{
                                        $service->status == 'Completed' ? 'success' :
                                        ($service->status == 'In Progress' ? 'warning' :
                                        ($service->status == 'Pending' ? 'secondary' : 'primary'))
                                    }}">
                                        {{ $service->status }}
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
                                                 alt="Service photo"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
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
                    <i class="bi bi-info-circle-fill me-2"></i>
                    No services assigned to you yet.
                </div>
            @endif
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Service Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Service Photo">
                </div>
            </div>
        </div>
    </div>

    <script>
        function updateModalImage(src) {
            document.getElementById('modalImage').src = src;
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const input = this.value.toLowerCase();
            const rows = document.querySelectorAll('#serviceTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });
    </script>

    <style>
        .thumbnail-img {
            cursor: pointer;
            transition: transform 0.2s;
        }

        .thumbnail-img:hover {
            transform: scale(1.05);
        }

        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
    </style>
@endsection
