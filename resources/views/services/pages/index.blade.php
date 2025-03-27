@extends('backend.layouts.app')

@section('title','service-form')


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
        <h4>Company List</h4>
        <a href="{{ route('services.create') }}" class="btn btn-primary">Service Creation</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card-body">
        <table id="datatablesSimple" class="table">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Booking Date</th>
                    <th>Customer Name</th>
                    <th>Vehicle Number</th>
                    <th>Vehicle Model</th>
                    <th>Contact Number</th>

                    <th>Delivery date </th>
                    <th>Employee Status  </th>
                    <th>Status  </th>
                    <th> Cost</th>
                    <th> Photo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($services as $service)
                <tr>
                    <td>{{ $service->booking_id }}</td>
                    <td>{{ $service->booking_date }}</td>
                    <td>{{ $service->customer_name }}</td>
                    <td>{{ $service->vehicle_number }}</td>
                    <td>{{ $service->vehicle_model  }}</td>
                    <td>{{ $service->contact_number_1 }}</td>
                    <td>{{ $service->expected_delivery_date	 }}</td>
                    <td>{{ $service->service_status	 }}</td>
                    <td>{{ $service->status	 }}</td>
                    <td>{{ $service->cost }}</td>










                    <td>
                        @if (!empty($service->photos) && is_string($service->photos))
                            @foreach (json_decode($service->photos, true) as $index => $photo)
                            <img src="{{ asset('storage/' . $photo) }}"
                            width="80" height="50"
                            class="img-thumbnail"
                            style="cursor: pointer;"
                            data-bs-toggle="modal"
                            data-bs-target="#imageModal"
                            onclick="showImage('{{ asset('storage/' . $photo) }}')">

                            @endforeach
                        @else
                            No Photos
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary btn-sm">Edit</a>

                            <form action="{{ route('services.destroy', $service->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </div>
                    </td>


                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="largeImage" src="" alt="Large Preview" class="img-fluid">
            </div>
        </div>
    </div>
</div>




@endsection
