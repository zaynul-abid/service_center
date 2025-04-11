@extends('backend.layouts.app')

@section('title','service-List')


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
            <h4>Admin List</h4>
            <a href="{{ route('services.create') }}" class="btn btn-primary">Create Admin</a>
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
            <table id="datatablesSimple" class="table">
                <thead class="table-light">
                <tr>
                    <th class>Booking ID</th>
                    <th>Date</th>
                    <th >Customer</th>
                    <th >Vehicle</th>
                    <th >Contact</th>
                    <th >Delivery</th>
                    <th >Status</th>
                    <th >Emp Status</th>
                    <th>Cost</th>
                    <th>Photos</th>
                    <th>Actions</th>
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
    </div>
    </div>
    </div>



@endsection
