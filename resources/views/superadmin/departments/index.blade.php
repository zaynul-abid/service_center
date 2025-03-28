@extends('backend.layouts.app')

@section('title', 'Departments')

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

    {{-- Success & Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="col-lg-8 mx-auto mt-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title fw-semibold mb-0">Department List</h5>
                    <a href="{{ route('departments.create') }}" class="btn btn-primary">Add Department</a>
                </div>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->description }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                {{ $departments->links() }}
            </div>
        </div>
    </div>

@endsection 
