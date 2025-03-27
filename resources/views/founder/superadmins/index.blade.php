@extends('backend.layouts.app')

@section('title','Company Creation')

@section('navbar')
@include('founder.partials.navbar')

@endsection
@section('content')

{{-- Success Message --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Error Message --}}
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>There were some errors with your input:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="col-lg-12">
    <div class="card w-100">
        <div class="card-body">
            <div class="d-sm-flex d-block align-items-center justify-content-between mb-4">
                <h5 class="card-title fw-semibold">Super Admins List</h5>
                <a href="{{ route('superadmins.create') }}" class="btn btn-primary">Add New</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Company Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($superadmins as $key => $superadmin)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $superadmin->name }}</td>
                                <td>{{ $superadmin->email }}</td>
                                <td>{{ $superadmin->company ?  $superadmin->company->company_name : 'No Company' }}</td>
                                <td>
                                    <a href="{{ route('superadmins.edit', $superadmin->id) }}" class="btn btn-sm btn-warning">
                                        <i class="ti ti-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('superadmins.destroy', $superadmin->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if ($superadmins->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted">No Super Admins found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
