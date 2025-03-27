@extends('backend.layouts.app')

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
    <div class="card-header">
        <h4>Service Report</h4>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sl.No</th>
                    <th>Booking Number</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Vehicle Number</th>
                    <th>Vehicle Model</th>
                    <th>Contact Number</th>
                    <th>Service Details</th>
                    <th>Cost</th>
                    <th>Assigned Employee</th>
                    <th>Status</th>
                    <th>Delivery Date</th>
                
                 
                </tr>
            </thead>
            <tbody>
                @foreach($services as $index => $service)
                <tr>
                    <td>{{ $loop->iteration }}</td> 
                    <td>{{ $service->booking_id }}</td>
                    <td>{{ $service->booking_date }}</td>
                    <td>{{ $service->customer_name }}</td>
                    <td>{{ $service->vehicle_number }}</td>
                    <td>{{ $service->vehicle_model }}</td>
                    <td>{{ $service->contact_number_1 }}</td>
                    <td>{{ $service->service_details }}</td>
                    <td>{{ $service->cost }}</td>
                    <td>{{ $service->employee ? $service->employee->name : 'Not Assigned' }}</td>
                    <td>{{ $service->service_status }}</td>
                    <td>{{ $service->expected_delivery_date }}</td>
                 
                  
                    </tr>
                @endforeach
            </tbody>
        </table>

      
        <div class="mt-3">
            <a href="{{ route('report.service.download') }}" class="btn btn-primary">Download PDF</a>
        </div>
    </div>
</div>
@endsection
