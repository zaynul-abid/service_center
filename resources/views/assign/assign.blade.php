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
    <div class="card-header">
        <h4>Assign Enquiry to Employees</h4>
    </div>
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
    <div class="card-body">
        <form action="{{ route('store.assign') }}" method="POST">
            @csrf
            <div class="mb-3">
                <select name="employee_id" class="form-select" required>
                    <option value="" disabled selected>Select Employee</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                    @endforeach
                </select>
            </div>
        
            <table id="datatablesSimple" class="table">
                <thead>
                    <tr>
                        <th data-sortable="false" style="width: 4.67%;"></th>

                        <th>Serial Number</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Customer Name</th>
                        <th>Contact Number</th>
                        <th>Status</th>
                        <th>Vehicle Number</th>
                        <th>Employee</th>
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $service)
                        <tr>
                            <td><input type="checkbox" name="service_ids[]" value="{{ $service->id }}"></td>
                            <td>{{ $service->booking_id }}</td>
                            <td>{{ $service->booking_date }}</td>
                            <td>{{ $service->booking_time }}</td>
                            <td>{{ $service->customer_name }}</td>
                            <td>{{ $service->contact_number_1 }}</td>
                            <td>{{ $service->status }}</td>
                            <td>{{ $service->vehicle_number }}</td>
                        

                            <td><strong>{{ strtoupper($service->employee->name ?? 'NOT ASSIGNED') }}</strong></td>

                           
                        </tr>
                    @endforeach
                </tbody>
            </table>
        
            <button type="submit" class="btn btn-success mt-3">Assign Selected Enquiries</button>
        </form>
        </div>

</div>
        
        <script>
            document.getElementById('select-all').addEventListener('click', function() {
                let checkboxes = document.querySelectorAll('input[name="service_ids[]"]');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });
        </script>


@endsection