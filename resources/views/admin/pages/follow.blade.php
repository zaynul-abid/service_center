@extends('backend.layouts.app')

@section('title', 'Service Follow-Up')
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
<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Service Follow-Ups</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Track and Manage Service Follow-Ups</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <h4>Service Follow-Up Details</h4>
            </div>

            <!-- Display Success and Warning Messages -->
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Follow-Up Search Form -->
            <form action="{{ route('service.followups') }}" method="GET" class="mb-4 p-3 bg-light border rounded">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="number" name="search_days" min="0" class="form-control"
                               placeholder="Enter days (0 for overdue)" value="{{ request('search_days') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>

            <div class="card-body">
                <table id="datatablesSimple" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Booking Date</th>
                            <th>Expected Delivery</th>
                            <th>Days Left</th> <!-- New Column -->
                            <th>Employee Status</th>
                            <th>Service Status</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Technician</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $today = \Carbon\Carbon::today();
                        @endphp
                        @foreach ($services as $service)
                            @php
                                $daysLeft = $service->days_difference;

                                if ($daysLeft < 0) {
                                    // Overdue
                                    $badgeClass = 'bg-danger';
                                    $daysText = 'Overdue by ' . abs($daysLeft) . ' day'.(abs($daysLeft) !== 1 ? 's' : '');
                                } elseif ($daysLeft == 0) {
                                    // Due today
                                    $badgeClass = 'bg-warning text-dark';
                                    $daysText = 'Due today';
                                } else {
                                    // Future delivery
                                    $badgeClass = 'bg-success';
                                    $daysText = $daysLeft . ' day'.($daysLeft !== 1 ? 's' : '').' remaining';
                                }
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($service->booking_date)->format('d-M-Y') }}
                                    @if(\Carbon\Carbon::parse($service->booking_date)->isToday())
                                        <br><small>(Today)</small>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('d-M-Y') }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $daysText }}</span>
                                </td>
                                <td>{{ $service->service_status }}</td>
                                <td>{{ $service->status }}</td>
                                <td>{{ $service->customer_name }}</td>
                                <td>{{ $service->contact_number_1 }}</td>
                                <td>{{ $service->employee->name ?? 'Unassigned' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


        </div>
    </div>
</main>
@endsection
