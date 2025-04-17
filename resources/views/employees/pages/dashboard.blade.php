@extends('employees.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0">Recent Services</h5>
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

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="serviceTable" class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-3">Booking #</th>
                        <th>Vehicle</th>
                        <th class="d-none d-md-table-cell">Model</th>
                        <th class="d-none d-lg-table-cell">Customer</th>
                        <th class="d-none d-xl-table-cell">Contact</th>
                        <th class="d-none d-xl-table-cell">Complaint</th>
                        <th>Delivery</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($assignedServices as $service)
                        <tr class="border-bottom">
                            <td class="ps-3">{{ $service->booking_id }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $service->vehicle_number }}</span>
                                    <small class="text-muted d-md-none">{{ $service->vehicle_model }}</small>
                                </div>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $service->vehicle_model }}</td>
                            <td class="d-none d-lg-table-cell">{{ $service->customer_name }}</td>
                            <td class="d-none d-xl-table-cell">{{ $service->contact_number_1 }}</td>
                            <td class="d-none d-xl-table-cell">
                                <div class="text-truncate" style="max-width: 150px;" title="{{ $service->customer_complaint }}">
                                    {{ $service->customer_complaint }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('M d') }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <span class="badge bg-{{
                                        $service->service_status == 'Completed' ? 'success' :
                                        ($service->service_status == 'In Progress' ? 'warning' :
                                        ($service->service_status == 'Pending' ? 'secondary' : 'primary'))
                                    }} text-uppercase fs-11">
                                      You  : {{ $service->service_status }}
                                    </span>
                                    <span class="badge bg-{{
                                        $service->status == 'Completed' ? 'success' :
                                        ($service->status == 'In Progress' ? 'warning' :
                                        ($service->status == 'Pending' ? 'secondary' : 'primary'))
                                    }} text-uppercase fs-11">
                                        Admin: {{ $service->status }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if (!empty($service->photos) && is_string($service->photos) && !empty(json_decode($service->photos, true)))
                                        <button class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#imageCarouselModal"
                                                onclick="initCarousel({{ json_encode(array_map(function($photo) { return asset('storage/' . $photo); }, json_decode($service->photos, true))) }})">
                                            <i class="bi bi-images"></i> View
                                        </button>
                                    @else
                                        <span class="text-muted small align-self-center">No Photos</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if($assignedServices->isEmpty())
                <div class="alert alert-info m-3">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    No services assigned to you yet.
                </div>
            @endif

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3" id="pagination-container">
                @if($assignedServices instanceof \Illuminate\Pagination\AbstractPaginator)
                    {{ $assignedServices->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>

    <!-- Image Carousel Modal -->
    <div class="modal fade" id="imageCarouselModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Service Photos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="serviceImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carousel-inner">
                            <!-- Images will be inserted here by JavaScript -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#serviceImagesCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#serviceImagesCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize carousel with images
        function initCarousel(images) {
            const carouselInner = document.getElementById('carousel-inner');
            carouselInner.innerHTML = '';

            images.forEach((image, index) => {
                const item = document.createElement('div');
                item.className = `carousel-item ${index === 0 ? 'active' : ''}`;
                item.innerHTML = `
                    <img src="${image}" class="d-block w-100" alt="Service photo" style="max-height: 70vh; object-fit: contain;">
                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
                        <p class="mb-0">Image ${index + 1} of ${images.length}</p>
                    </div>
                `;
                carouselInner.appendChild(item);
            });
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
        .table {
            font-size: 0.875rem;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
        }

        .table td, .table th {
            padding: 0.75rem;
            vertical-align: middle;
            border-color: #f0f0f0;
        }

        .badge {
            font-size: 0.7rem;
            padding: 0.35em 0.5em;
            font-weight: 500;
        }

        .fs-11 {
            font-size: 0.6875rem !important;
        }

        .carousel-item img {
            background-color: #f8f9fa;
        }

        .carousel-caption {
            right: auto;
            left: 50%;
            transform: translateX(-50%);
            bottom: 20px;
            padding: 0.5rem 1rem;
        }

        /* Add side margins and shadow to the table */
        .table-responsive {
            margin-left: 1rem;  /* Left margin */
            margin-right: 1rem; /* Right margin */
        }

        #serviceTable {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            border-radius: 8px; /* Optional: Rounded corners to match card */
            overflow: hidden; /* Ensure shadow applies to the entire table */
        }

        /* Ensure the table header and body align with the shadow */
        #serviceTable thead {
            background-color: #f8f9fa;
        }

        #serviceTable tbody tr:hover {
            background-color: #f1f1f1; /* Slight hover effect for better visibility */
        }
    </style>
@endsection
