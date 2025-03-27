@extends('backend.layouts.app')

@section('title','Company-Index')

@section('navbar')
@include('founder.partials.navbar')

@endsection
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Company List</h4>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">Create Company</a>
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
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Registration Number</th>
                    <th>Plan</th>
                    <th>Subscription Start</th>
                    <th>Subscription End</th>
                    <th>Action</th>
                </tr>
            </thead>


            <tbody>
                @foreach($companies as $company)

                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->company_name }}</td>
                        <td>{{ $company->contact_number }}</td>
                        <td>{{ $company->address }}</td>

                        <td>{{ $company->registration_number }}</td>
                        <td>{{ $company->plan }}</td>
                        <td>{{ $company->subscription_start_date }}</td>
                        <td>{{ $company->subscription_end_date }}</td>
                        <td>

                            <div class="d-flex gap-2">
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
