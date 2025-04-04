@extends('backend.layouts.app')

@section('title','Superadmin Creation')

@section('navbar')
@include('founder.partials.navbar')

@endsection
@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Superadmin List</h4>
            <a href="{{ route('superadmins.create') }}" class="btn btn-primary">Create SuperAdmin</a>
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
                    </tbody>
            </table>
        </div>
    </div>

@endsection
