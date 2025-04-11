@extends('backend.layouts.app')

@section('title', 'Service Management')

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
    <div class="container-fluid p-2">
        <div class="card border-0 bg-white rounded-lg shadow-xs">
            <div class="card-header bg-transparent border-0 p-2 pb-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h4 class="mb-2 mb-md-0 text-gray-900 fw-semibold">Service Bookings</h4>
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <!-- Search Form -->
                        <form method="GET" action="{{ route('services.index') }}" class="mb-2 mb-md-0">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search bookings..."
                                       value="{{ request('search') }}" style="min-width: 250px;">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('services.index') }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                        <a href="{{ route('services.create') }}" class="btn btn-primary px-4 py-2 rounded-md">
                            <i class="bi bi-plus-lg me-2"></i>New Booking
                        </a>
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

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <div class="card-body p-2 p-md-4">
                <!-- Results Count -->
                <div class="mb-3 text-muted small">
                    Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} entries
                    @if(request('search'))
                        matching "{{ request('search') }}"
                    @endif
                </div>

                <div class="table-responsive">
                    <div class="d-none d-md-block">
                        <!-- Desktop table -->
                        <table class="table table-hover align-middle mb-0 w-100" id="serviceTable">
                            <thead>
                            <tr class="text-gray-700">
                                <th class="ps-3 border-end">Booking ID</th>
                                <th class="border-end">Date</th>
                                <th class="border-end">Customer</th>
                                <th class="border-end">Vehicle</th>
                                <th class="border-end">Contact</th>
                                <th class="border-end">Delivery</th>
                                <th class="border-end">Status</th>
                                <th class="border-end">Emp Status</th>
                                <th class="border-end">Cost</th>
                                <th class="border-end">Photos</th>
                                <th class="pe-3 text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($services as $service)
                                <tr>
                                    <td class="ps-3 fw-medium text-primary border-end">{{ $service->booking_id }}</td>
                                    <td class="border-end">
                                        <div class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($service->booking_date)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="fw-medium border-end">{{ $service->customer_name }}</td>
                                    <td class="border-end">
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $service->vehicle_number }}</span>
                                            <small class="text-gray-500">{{ $service->vehicle_model }}</small>
                                        </div>
                                    </td>
                                    <td class="border-end">{{ $service->contact_number_1 }}</td>
                                    <td class="border-end">
                                        <div class="text-sm text-gray-600">
                                            {{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="border-end">
                                    <span class="badge rounded-pill py-1 px-3
                                        @if($service->status === 'completed') bg-success-light text-success
                                        @elseif($service->status === 'in_progress') bg-warning-light text-warning
                                        @elseif($service->status === 'pending') bg-info-light text-info
                                        @else bg-secondary-light text-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                    </span>
                                    </td>
                                    <td class="border-end">
                                    <span class="badge rounded-pill py-1 px-3
                                        @if($service->service_status === 'Requested') bg-dark text-light
                                        @elseif($service->service_status === 'completed') bg-success-light text-success
                                        @elseif($service->service_status === 'in_progress') bg-warning-light text-warning
                                        @elseif($service->service_status === 'accepted') bg-info-light text-info
                                        @elseif($service->service_status === 'rejected') bg-indigo-700-light text-info
                                        @else bg-secondary-light text-secondary @endif">
                                        {{ ucfirst(str_replace('_', ' ', $service->service_status)) }}
                                    </span>
                                    </td>
                                    <td class="fw-medium text-gray-900 border-end">₹{{ number_format($service->cost, 2) }}</td>
                                    <td class="border-end">
                                        @if (!empty($service->photos) && is_string($service->photos))
                                            <div class="d-flex gap-2 flex-wrap">
                                                @foreach (json_decode($service->photos, true) as $photo)
                                                    <div class="avatar-xs">
                                                        <img src="{{ asset('storage/' . $photo) }}"
                                                             class="rounded-2 object-fit-cover cursor-pointer w-100 h-100"
                                                             data-bs-toggle="modal"
                                                             data-bs-target="#imageModal"
                                                             onclick="showImage('{{ asset('storage/' . $photo) }}')"
                                                             style="cursor: pointer;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-gray-400 small">-</span>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('services.edit', $service->id) }}"
                                               class="btn btn-sm btn-icon btn-outline-primary rounded-circle"
                                               title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('services.destroy', $service->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm btn-icon btn-outline-danger rounded-circle"
                                                        title="Delete"
                                                        onclick="return confirm('Are you sure?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        No bookings found
                                        @if(request('search'))
                                            matching "{{ request('search') }}"
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <!-- Desktop Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $services->appends(request()->query())->links('vendor.pagination.bootstrap-4', [
                                'previousPageText' => '<i class="bi bi-chevron-left"></i>',
                                'nextPageText' => '<i class="bi bi-chevron-right"></i>'
                            ]) }}
                        </div>
                    </div>

                    <!-- Mobile cards -->
                    <div class="d-block d-md-none">
                        @forelse ($services as $service)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <!-- Your existing mobile card content -->
                                </div>
                            </div>
                        @empty
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body text-center py-4 text-muted">
                                    No bookings found
                                    @if(request('search'))
                                        matching "{{ request('search') }}"
                                    @endif
                                </div>
                            </div>
                        @endforelse

                        <!-- Mobile Pagination -->
                        <div class="d-block d-md-none mt-4">
                            {{ $services->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-primary">Photo Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 text-center">
                    <img id="largeImage" src="" alt="Image Preview" class="img-fluid rounded-3 shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImage(src) {
            document.getElementById('largeImage').src = src;
        }
    </script>

    <style>
        /* Custom pagination styles */
        .pagination {
            font-size: 0.875rem; /* Smaller font size */
        }

        .pagination .page-link {
            padding: 0.25rem 0.5rem; /* Smaller padding */
            min-width: 32px; /* Consistent width */
            text-align: center;
        }

        /* Make arrow buttons slightly larger than number buttons */
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            padding: 0.25rem 0.65rem;
        }

        /* Active state styling */
        .pagination .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        /* Hover state */
        .pagination .page-link:hover {
            background-color: #e9ecef;
        }
    </style>
@endsection
