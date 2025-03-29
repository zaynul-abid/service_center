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
        <h4>Employee Report</h4>
    </div>


    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sl.No</th> {{-- Order Number --}}
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Phone Number</th>
{{--                    <th>Company</th>--}}
                    <th>Email</th>
                    <th>Department</th>


                </tr>
            </thead>
            <tbody>
                @foreach($employees as $index => $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td> {{-- Numeric order starting from 1 --}}
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->phone }}</td>
{{--                         <td>{{ $employee->company->company_name }}</td>--}}
                        <td>{{ $employee->email }}</td>

                        <td>{{ $employee->department ? $employee->department->name : 'N/A' }}</td> {{-- Department Name --}}


                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <a href="{{ route('report.employee.download') }}" class="btn btn-primary">Download PDF</a>
        </div>
    </div>
</div>

@endsection
