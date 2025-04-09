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
                    <a href="{{ route('services.create') }}" class="btn btn-primary px-4 py-2 rounded-md">
                        <i class="bi bi-plus-lg me-2"></i>New Booking
                    </a>
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
                            @foreach ($services as $service)
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
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile cards -->
                    <div class="d-block d-md-none">
                        @foreach ($services as $service)
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title text-primary mb-0">{{ $service->booking_id }}</h5>
                                        <div class="d-flex gap-2">
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
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Customer:</strong></p>
                                            <p>{{ $service->customer_name }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Contact:</strong></p>
                                            <p>{{ $service->contact_number_1 }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Vehicle:</strong></p>
                                            <p>{{ $service->vehicle_number }}<br>
                                                <small class="text-gray-500">{{ $service->vehicle_model }}</small></p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Cost:</strong></p>
                                            <p>₹{{ number_format($service->cost, 2) }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Booking Date:</strong></p>
                                            <p>{{ \Carbon\Carbon::parse($service->booking_date)->format('d M Y') }}</p>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Delivery:</strong></p>
                                            <p>{{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('d M Y') }}</p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Status:</strong></p>
                                            <span class="badge rounded-pill py-1 px-3
                                            @if($service->status === 'completed') bg-success-light text-success
                                            @elseif($service->status === 'in_progress') bg-warning-light text-warning
                                            @elseif($service->status === 'pending') bg-info-light text-info
                                            @else bg-secondary-light text-secondary @endif">
                                            {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                                        </span>
                                        </div>
                                        <div class="col-6">
                                            <p class="mb-1"><strong>Emp Status:</strong></p>
                                            <span class="badge rounded-pill py-1 px-3
                                            @if($service->service_status === 'Requested') bg-dark text-light
                                            @elseif($service->service_status === 'completed') bg-success-light text-success
                                            @elseif($service->service_status === 'in_progress') bg-warning-light text-warning
                                            @elseif($service->service_status === 'accepted') bg-info-light text-info
                                            @elseif($service->service_status === 'rejected') bg-indigo-700-light text-info
                                            @else bg-secondary-light text-secondary @endif">
                                            {{ ucfirst(str_replace('_', ' ', $service->service_status)) }}
                                        </span>
                                        </div>
                                    </div>

                                    @if (!empty($service->photos) && is_string($service->photos))
                                        <div class="mt-2">
                                            <p class="mb-1"><strong>Photos:</strong></p>
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
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
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
@endsection
