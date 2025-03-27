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
                            <th>Service Status</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Technician</th>
                        </tr>
                    </thead>
                    {{-- <tbody>
                        @php
                            use Carbon\Carbon;
                            $today = Carbon::today();
                        @endphp
            
                        @foreach($services as $service)
                            @php
                                $bookingDate = Carbon::parse($service->booking_date);
                                $expectedDeliveryDate = Carbon::parse($service->expected_delivery_date);
            
                                // Calculate how many days are left (overdue will show negative days)
                                $daysLeft = $expectedDeliveryDate->diffInDays($today, false); // Keep the sign (+/-)
                            @endphp
            
                            <tr @if($daysLeft < 0) class="table-danger" @elseif($expectedDeliveryDate->isToday()) class="table-warning" @endif>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $bookingDate->format('d-M-Y') }}<br>
                                    <small class="text-muted">
                                        @if($bookingDate->isToday())
                                            (Today)
                                        @elseif($bookingDate->lt($today))
                                            ({{ $bookingDate->diffInDays($today) }} days ago)
                                        @else
                                            (in {{ $bookingDate->diffInDays($today) }} days)
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    {{ $expectedDeliveryDate->format('d-M-Y') ?? 'N/A' }}<br>
                                    @if($daysLeft < 0)
                                        <span class="badge bg-danger text-white">Overdue by {{ abs($daysLeft) }} days</span>
                                    @elseif($expectedDeliveryDate->isToday())
                                        <span class="badge bg-warning text-dark">Due Today</span>
                                    @else
                                        <span class="badge bg-success text-white">{{ $daysLeft }} days remaining</span>
                                    @endif
                                </td>
            
                                <!-- Modified "Days Left" Column -->
                                <td>
                                    @if($daysLeft < 0)
                                        <span class="text-danger">{{ abs($daysLeft) }} days overdue</span> <!-- Overdue in Red -->
                                    @elseif($daysLeft === 0)
                                        <span class="text-warning">Due today</span>
                                    @else
                                        <span class="text-success">{{ $daysLeft }} days left</span> <!-- Remaining Days in Green -->
                                    @endif
                                </td>
            
                                <td>{{ $service->service_status }}</td>
                                <td>{{ $service->customer_name }}</td>
                                <td>{{ $service->contact_number_1 }}</td>
                                <td>{{ $service->employee->name ?? 'Unassigned' }}</td>
                            </tr>
                        @endforeach
                    </tbody> --}}
                    <tbody>
                        @php
                            $today = \Carbon\Carbon::today();
                        @endphp
            
                        @foreach ($services as $service)
                            @php
                                $expectedDeliveryDate = \Carbon\Carbon::parse($service->expected_delivery_date);
                                $daysLeft = $expectedDeliveryDate->diffInDays($today, false); // Calculate days difference
            
                                // Determine if overdue or remaining days based on days difference
                                if ($daysLeft < 0) {
                                    $badgeClass = 'bg-success'; // Green badge for remaining days
                                    $daysText = abs($daysLeft) . ' days remaining';
                                } else {
                                    $badgeClass = 'bg-danger'; // Red badge for overdue days
                                    $daysText = 'Overdue by ' . $daysLeft . ' days';
                                }
                            @endphp
            
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($service->booking_date)->format('d-M-Y') }} <br><small>(Today)</small></td>
                                <td>{{ $expectedDeliveryDate->format('d-M-Y') }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">{{ $daysText }}</span>
                                </td>
                                <td>{{ $service->service_status }}</td>
                                <td>{{ $service->customer_name }}</td>
                                <td>{{ $service->contact_number_1 }}</td>
                                <td>{{$service->employee->name ?? 'Unassigned' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            

        </div>
    </div>
</main>
@endsection