<!-- resources/views/admin/users/recover.blade.php -->
@extends('backend.layouts.app')

@section('title','force-delete')


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
    <div class="container-fluid">
        <h4 class="mb-4">Recycle Bin</h4>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <ul class="nav nav-tabs" id="recycleBinTabs" role="tablist">
            <!-- Your existing tab structure here -->
        </ul>




            <ul class="nav nav-tabs" id="recycleBinTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="employees-tab" data-toggle="tab" href="#employees" role="tab">Employees</a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" id="customers-tab" data-toggle="tab" href="#customers" role="tab">Customers</a>--}}
{{--                </li>--}}
                <li class="nav-item">
                    <a class="nav-link" id="departments-tab" data-toggle="tab" href="#departments" role="tab">Departments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="services-tab" data-toggle="tab" href="#services" role="tab">Services</a>
                </li>
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" id="vehicles-tab" data-toggle="tab" href="#vehicles" role="tab">Vehicles</a>--}}
{{--                </li>--}}
            </ul>

            <div class="tab-content p-3 border border-top-0 rounded-bottom" id="recycleBinTabsContent">
                <!-- Users Tab -->
                <div class="tab-pane fade show active" id="users" role="tabpanel">
                    <h5>Deleted Admin</h5>
                    @if($deletedUsers->isEmpty())
                        <div class="alert alert-info">No deleted admin found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedUsers as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->deleted_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('softdelete.restore.user', $user->id) }}" class="btn btn-sm btn-success" title="Restore">
                                                <i class="fas fa-trash-restore"></i> Restore
                                            </a>
                                            <form method="POST" id="deleteForm{{ $user->id }}" action="{{ route('softdelete.forceDelete.user', $user->id) }}" style="display:inline;"
                                                  onsubmit="return confirm('Are you sure you want to permanently delete {{ $user->name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete Permanently
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $deletedUsers->links() }}
                    @endif
                </div>

                <!-- Employees Tab -->
                <div class="tab-pane fade" id="employees" role="tabpanel">
                    <h5>Deleted Employees</h5>
                    @if($deletedEmployees->isEmpty())
                        <div class="alert alert-info">No deleted employees found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedEmployees as $employee)
                                    <tr>
                                        <td>{{ $employee->id }}</td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ $employee->position }}</td>
                                        <td>{{ $employee->department->name ?? 'N/A' }}</td>
                                        <td>{{ $employee->deleted_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('softdelete.restore.employee', $employee->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-trash-restore"></i> Restore
                                            </a>
                                            <form method="POST" id="deleteForm{{ $employee->id }}" action="{{ route('softdelete.forceDelete.employee',$employee->id) }}" style="display:inline;"
                                                  onsubmit="return confirm('Are you sure you want to permanently delete {{ $employee->name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete Permanently
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $deletedEmployees->links() }}
                    @endif
                </div>

{{--                <!-- Customers Tab -->--}}
{{--                <div class="tab-pane fade" id="customers" role="tabpanel">--}}
{{--                    <h3>Deleted Customers</h3>--}}
{{--                    @if($deletedCustomers->isEmpty())--}}
{{--                        <div class="alert alert-info">No deleted customers found.</div>--}}
{{--                    @else--}}
{{--                        <div class="table-responsive">--}}
{{--                            <table class="table table-striped table-hover">--}}
{{--                                <thead class="thead-dark">--}}
{{--                                <tr>--}}
{{--                                    <th>ID</th>--}}
{{--                                    <th>Name</th>--}}
{{--                                    <th>Email</th>--}}
{{--                                    <th>Phone</th>--}}
{{--                                    <th>Deleted At</th>--}}
{{--                                    <th>Actions</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @foreach($deletedCustomers as $customer)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{ $customer->id }}</td>--}}
{{--                                        <td>{{ $customer->name }}</td>--}}
{{--                                        <td>{{ $customer->email }}</td>--}}
{{--                                        <td>{{ $customer->phone }}</td>--}}
{{--                                        <td>{{ $customer->deleted_at->format('Y-m-d H:i:s') }}</td>--}}
{{--                                        <td>--}}
{{--                                            <a href="{{ route('softdelete.restore.customer', $customer->id) }}" class="btn btn-sm btn-success">--}}
{{--                                                <i class="fas fa-trash-restore"></i> Restore--}}
{{--                                            </a>--}}

{{--                                            <form method="POST" id="deleteForm{{ $customer->id }}" action="{{ route('softdelete.forceDelete.customer',$customer->id) }}" style="display:inline;"--}}
{{--                                                  onsubmit="return confirm('Are you sure you want to permanently delete {{ $customer->name }}? This action cannot be undone.');">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="btn btn-sm btn-danger">--}}
{{--                                                    <i class="fas fa-trash"></i> Delete Permanently--}}
{{--                                                </button>--}}
{{--                                            </form>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                        {{ $deletedCustomers->links() }}--}}
{{--                    @endif--}}
{{--                </div>--}}

                <!-- Departments Tab -->
                <div class="tab-pane fade" id="departments" role="tabpanel">
                    <h5>Deleted Departments</h5>
                    @if($deletedDepartments->isEmpty())
                        <div class="alert alert-info">No deleted departments found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedDepartments as $department)
                                    <tr>
                                        <td>{{ $department->id }}</td>
                                        <td>{{ $department->name }}</td>
                                        <td>{{ Str::limit($department->description, 50) }}</td>
                                        <td>{{ $department->deleted_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('softdelete.restore.department', $department->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-trash-restore"></i> Restore
                                            </a>
                                            <form method="POST" id="deleteForm{{ $department->id }}" action="{{ route('softdelete.forceDelete.department', $department->id) }}" style="display:inline;"
                                                  onsubmit="return confirm('Are you sure you want to permanently delete {{ $department->name }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete Permanently
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $deletedDepartments->links() }}
                    @endif
                </div>

                <!-- Services Tab -->
                <div class="tab-pane fade" id="services" role="tabpanel">
                    <h5>Deleted Services</h5>
                    @if($deletedServices->isEmpty())
                        <div class="alert alert-info">No deleted services found.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Booking ID</th>
                                    <th>Customer Name</th>
                                    <th>Vehicle Number</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedServices as $service)
                                    <tr>
                                        <td>{{ $service->id }}</td>
                                        <td>{{ $service->booking_id }}</td>
                                        <td>{{ $service->customer_name}}</td>
                                        <td>{{ $service->vehicle_number }}</td>
                                        <td>{{ $service->deleted_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('softdelete.restore.service', $service->id) }}" class="btn btn-sm btn-success">
                                                <i class="fas fa-trash-restore"></i> Restore
                                            </a>

                                            <!-- Form to handle DELETE request -->
                                            <form method="POST" id="deleteForm{{ $service->id }}" action="{{ route('softdelete.forceDelete.service', $service->id) }}" style="display:inline;"
                                                  onsubmit="return confirm('Are you sure you want to permanently delete {{ $service->booking_id }}? This action cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Delete Permanently
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $deletedServices->links() }}
                    @endif
                </div>

{{--                <!-- Vehicles Tab -->--}}
{{--                <div class="tab-pane fade" id="vehicles" role="tabpanel">--}}
{{--                    <h3>Deleted Vehicles</h3>--}}
{{--                    @if($deletedVehicles->isEmpty())--}}
{{--                        <div class="alert alert-info">No deleted vehicles found.</div>--}}
{{--                    @else--}}
{{--                        <div class="table-responsive">--}}
{{--                            <table class="table table-striped table-hover">--}}
{{--                                <thead class="thead-dark">--}}
{{--                                <tr>--}}
{{--                                    <th>ID</th>--}}
{{--                                    <th>Make</th>--}}
{{--                                    <th>Model</th>--}}
{{--                                    <th>Year</th>--}}
{{--                                    <th>License Plate</th>--}}
{{--                                    <th>Deleted At</th>--}}
{{--                                    <th>Actions</th>--}}
{{--                                </tr>--}}
{{--                                </thead>--}}
{{--                                <tbody>--}}
{{--                                @foreach($deletedVehicles as $vehicle)--}}
{{--                                    <tr>--}}
{{--                                        <td>{{ $vehicle->id }}</td>--}}
{{--                                        <td>{{ $vehicle->make }}</td>--}}
{{--                                        <td>{{ $vehicle->model }}</td>--}}
{{--                                        <td>{{ $vehicle->year }}</td>--}}
{{--                                        <td>{{ $vehicle->license_plate }}</td>--}}
{{--                                        <td>{{ $vehicle->deleted_at->format('Y-m-d H:i:s') }}</td>--}}
{{--                                        <td>--}}
{{--                                            <a href="{{ route('softdelete.restore.vehicle', $vehicle->id) }}" class="btn btn-sm btn-success">--}}
{{--                                                <i class="fas fa-trash-restore"></i> Restore--}}
{{--                                            </a>--}}
{{--                                            <button class="btn btn-sm btn-danger" data-toggle="modal" data-target="#confirmDeleteModal"--}}
{{--                                                    data-id="{{ $vehicle->id }}" data-type="vehicle" data-name="{{ $vehicle->make }} {{ $vehicle->model }}">--}}
{{--                                                <i class="fas fa-trash"></i> Delete Permanently--}}
{{--                                            </button>--}}

{{--                                            <form method="POST" id="deleteForm{{ $vehicle->id  }}" action="{{ route('softdelete.forceDelete.vehicle',$vehicle->id ) }}" style="display:inline;"--}}
{{--                                                  onsubmit="return confirm('Are you sure you want to permanently delete {{$vehicle->name }}? This action cannot be undone.');">--}}
{{--                                                @csrf--}}
{{--                                                @method('DELETE')--}}
{{--                                                <button type="submit" class="btn btn-sm btn-danger">--}}
{{--                                                    <i class="fas fa-trash"></i> Delete Permanently--}}
{{--                                                </button>--}}
{{--                                            </form>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                                </tbody>--}}
{{--                            </table>--}}
{{--                        </div>--}}
{{--                        {{ $deletedVehicles->links() }}--}}
{{--                    @endif--}}
{{--                </div>--}}
            </div>
        </div>

    @endsection



