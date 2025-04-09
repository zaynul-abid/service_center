@extends('backend.layouts.app')

@section('title', 'Employee List')


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
        <h4>Employee List</h4>
        <a href="{{ route('employees.create') }}" class="btn btn-primary">Create Employee</a>
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

    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>


    <div class="card-body">
        <table id="datatablesSimple" class="table">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Employee Status</th>


                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>{{ $employee->name }}</td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->phone ?? 'N/A' }}</td>
                            <td>{{ $employee->department->name ?? 'N/A' }}</td>

                            <td>
                                <span class="{{ empty($employee->position) ? 'text-danger' : '' }}">
                                    {{ $employee->position ?? 'N/A' }}
                                </span>
                            </td>




                            <td>
                                @if($employee->services->isNotEmpty())
                                    @foreach($employee->services as $service)
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary me-2">{{ $service->booking_id }}</span>
                                            <p class="m-0">{{ $service->employee_remarks ?? 'No Remarks' }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-danger">No Services Assigned</span>
                                @endif
                            </td>

                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-warning btn-sm">Edit</a>


                                        <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this employee?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>

                                </div>
                            </td>
                        </tr>
                    @endforeach

                        @if ($employees->isEmpty())
                            <tr>
                                <td colspan="8" class="text-center text-muted">No Employees found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{ $employees->links() }}
        </div>
    </div>
</div>

@endsection
