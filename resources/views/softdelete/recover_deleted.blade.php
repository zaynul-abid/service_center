@extends('backend.layouts.app')

@section('title','Recycle Bin')

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
    <div class="container-fluid px-0 px-md-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 mb-1"><i class="fas fa-recycle text-primary me-2"></i>Recycle Bin</h1>
                <p class="text-muted mb-0">Manage deleted items and restore when needed</p>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle flex-shrink-0 me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle flex-shrink-0 me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="card shadow-sm mb-4">
            <div class="card-body p-2">
                <ul class="nav nav-pills nav-fill flex-nowrap overflow-auto" id="recycleBinTabs" role="tablist" style="scrollbar-width: none;">
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-2 active" id="users-tab" data-bs-toggle="pill" data-bs-target="#users" type="button" role="tab">
                            <i class="fas fa-user-shield"></i>
                            <span>Admins</span>
                            <span class="badge bg-primary rounded-pill">{{ $deletedUsers->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-2" id="employees-tab" data-bs-toggle="pill" data-bs-target="#employees" type="button" role="tab">
                            <i class="fas fa-users"></i>
                            <span>Employees</span>
                            <span class="badge bg-primary rounded-pill">{{ $deletedEmployees->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-2" id="departments-tab" data-bs-toggle="pill" data-bs-target="#departments" type="button" role="tab">
                            <i class="fas fa-building"></i>
                            <span>Departments</span>
                            <span class="badge bg-primary rounded-pill">{{ $deletedDepartments->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item flex-shrink-0" role="presentation">
                        <button class="nav-link d-flex align-items-center gap-2" id="services-tab" data-bs-toggle="pill" data-bs-target="#services" type="button" role="tab">
                            <i class="fas fa-tools"></i>
                            <span>Services</span>
                            <span class="badge bg-primary rounded-pill">{{ $deletedServices->count() }}</span>
                        </button>
                    </li>
                    @if(auth()->user()->usertype === 'founder')
                        <li class="nav-item flex-shrink-0" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2" id="companies-tab" data-bs-toggle="pill" data-bs-target="#companies" type="button" role="tab">
                                <i class="fas fa-building"></i>
                                <span>Companies</span>
                                <span class="badge bg-primary rounded-pill">{{ $deletedCompanies->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item flex-shrink-0" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2" id="plans-tab" data-bs-toggle="pill" data-bs-target="#plans" type="button" role="tab">
                                <i class="fas fa-clipboard-list"></i>
                                <span>Plans</span>
                                <span class="badge bg-primary rounded-pill">{{ $deletedPlans->count() }}</span>
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content" id="recycleBinTabsContent">
            <!-- Users Tab -->
            <div class="tab-pane fade show active" id="users" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h5 class="mb-2 mb-md-0">
                                <i class="fas fa-user-shield text-primary me-2"></i>Deleted Admins
                            </h5>
                            <div class="text-muted small">
                                Showing {{ $deletedUsers->count() }} item{{ $deletedUsers->count() !== 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>

                    @if($deletedUsers->isEmpty())
                        <div class="card-body">
                            <div class="alert alert-light border text-center py-4">
                                <i class="fas fa-user-slash fa-2x text-muted mb-3"></i>
                                <h5 class="text-muted">No deleted admin accounts found</h5>
                                <p class="mb-0">Deleted admins will appear here</p>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th width="80">ID</th>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th width="160">Deleted</th>
                                    <th width="120" class="text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedUsers as $user)
                                    <tr>
                                        <td class="text-muted align-middle">#{{ $user->id }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle flex-shrink-0 me-2">
                                                    <i class="fas fa-user-circle fa-lg text-muted p-2"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $user->name }}</div>
                                                    <small class="text-muted">{{ $user->usertype }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="align-middle">{{ $user->email }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $user->deleted_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('softdelete.restore.user', $user->id) }}"
                                                   class="btn btn-outline-success rounded-start-2"
                                                   data-bs-toggle="tooltip"
                                                   title="Restore">
                                                    <i class="fas fa-trash-restore"></i>
                                                </a>
                                                <form method="POST" action="{{ route('softdelete.forceDelete.user', $user->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger rounded-end-2"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete Permanently"
                                                            onclick="return confirm('Permanently delete {{ $user->name }}?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($deletedUsers->hasPages())
                            <div class="card-footer bg-white">
                                {{ $deletedUsers->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Employees Tab -->
            <div class="tab-pane fade" id="employees" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h5 class="mb-2 mb-md-0">
                                <i class="fas fa-users text-primary me-2"></i>Deleted Employees
                            </h5>
                            <div class="text-muted small">
                                Showing {{ $deletedEmployees->count() }} item{{ $deletedEmployees->count() !== 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>

                    @if($deletedEmployees->isEmpty())
                        <div class="card-body">
                            <div class="alert alert-light border text-center py-4">
                                <i class="fas fa-user-times fa-2x text-muted mb-3"></i>
                                <h5 class="text-muted">No deleted employees found</h5>
                                <p class="mb-0">Deleted employees will appear here</p>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th width="80">ID</th>
                                    <th>Employee</th>
                                    <th>Position</th>
                                    <th>Department</th>
                                    <th width="160">Deleted</th>
                                    <th width="120" class="text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedEmployees as $employee)
                                    <tr>
                                        <td class="text-muted align-middle">#{{ $employee->id }}</td>
                                        <td class="align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded-circle flex-shrink-0 me-2">
                                                    <i class="fas fa-user-tie fa-lg text-muted p-2"></i>
                                                </div>
                                                <div class="fw-medium">{{ $employee->name }}</div>
                                            </div>
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">{{ $employee->position }}</span>
                                        </td>
                                        <td class="align-middle">
                                            {{ $employee->department->name ?? 'N/A' }}
                                        </td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $employee->deleted_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('softdelete.restore.employee', $employee->id) }}"
                                                   class="btn btn-outline-success rounded-start-2"
                                                   data-bs-toggle="tooltip"
                                                   title="Restore">
                                                    <i class="fas fa-trash-restore"></i>
                                                </a>
                                                <form method="POST" action="{{ route('softdelete.forceDelete.employee', $employee->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger rounded-end-2"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete Permanently"
                                                            onclick="return confirm('Permanently delete {{ $employee->name }}?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($deletedEmployees->hasPages())
                            <div class="card-footer bg-white">
                                {{ $deletedEmployees->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Departments Tab -->
            <div class="tab-pane fade" id="departments" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h5 class="mb-2 mb-md-0">
                                <i class="fas fa-building text-primary me-2"></i>Deleted Departments
                            </h5>
                            <div class="text-muted small">
                                Showing {{ $deletedDepartments->count() }} item{{ $deletedDepartments->count() !== 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>

                    @if($deletedDepartments->isEmpty())
                        <div class="card-body">
                            <div class="alert alert-light border text-center py-4">
                                <i class="fas fa-building-circle-xmark fa-2x text-muted mb-3"></i>
                                <h5 class="text-muted">No deleted departments found</h5>
                                <p class="mb-0">Deleted departments will appear here</p>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th width="80">ID</th>
                                    <th>Department</th>
                                    <th>Description</th>
                                    <th width="160">Deleted</th>
                                    <th width="120" class="text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedDepartments as $department)
                                    <tr>
                                        <td class="text-muted align-middle">#{{ $department->id }}</td>
                                        <td class="align-middle fw-medium">{{ $department->name }}</td>
                                        <td class="align-middle text-muted">{{ Str::limit($department->description, 50) }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $department->deleted_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('softdelete.restore.department', $department->id) }}"
                                                   class="btn btn-outline-success rounded-start-2"
                                                   data-bs-toggle="tooltip"
                                                   title="Restore">
                                                    <i class="fas fa-trash-restore"></i>
                                                </a>
                                                <form method="POST" action="{{ route('softdelete.forceDelete.department', $department->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger rounded-end-2"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete Permanently"
                                                            onclick="return confirm('Permanently delete {{ $department->name }}?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($deletedDepartments->hasPages())
                            <div class="card-footer bg-white">
                                {{ $deletedDepartments->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Services Tab -->
            <div class="tab-pane fade" id="services" role="tabpanel">
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                            <h5 class="mb-2 mb-md-0">
                                <i class="fas fa-tools text-primary me-2"></i>Deleted Services
                            </h5>
                            <div class="text-muted small">
                                Showing {{ $deletedServices->count() }} item{{ $deletedServices->count() !== 1 ? 's' : '' }}
                            </div>
                        </div>
                    </div>

                    @if($deletedServices->isEmpty())
                        <div class="card-body">
                            <div class="alert alert-light border text-center py-4">
                                <i class="fas fa-screwdriver-wrench fa-2x text-muted mb-3"></i>
                                <h5 class="text-muted">No deleted services found</h5>
                                <p class="mb-0">Deleted services will appear here</p>
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                <tr>
                                    <th width="80">ID</th>
                                    <th>Booking</th>
                                    <th>Customer</th>
                                    <th>Vehicle</th>
                                    <th width="160">Deleted</th>
                                    <th width="120" class="text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($deletedServices as $service)
                                    <tr>
                                        <td class="text-muted align-middle">#{{ $service->id }}</td>
                                        <td class="align-middle fw-medium">{{ $service->booking_id }}</td>
                                        <td class="align-middle">{{ $service->customer_name }}</td>
                                        <td class="align-middle">{{ $service->vehicle_number }}</td>
                                        <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $service->deleted_at->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('softdelete.restore.service', $service->id) }}"
                                                   class="btn btn-outline-success rounded-start-2"
                                                   data-bs-toggle="tooltip"
                                                   title="Restore">
                                                    <i class="fas fa-trash-restore"></i>
                                                </a>
                                                <form method="POST" action="{{ route('softdelete.forceDelete.service', $service->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger rounded-end-2"
                                                            data-bs-toggle="tooltip"
                                                            title="Delete Permanently"
                                                            onclick="return confirm('Permanently delete service #{{ $service->booking_id }}?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($deletedServices->hasPages())
                            <div class="card-footer bg-white">
                                {{ $deletedServices->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Companies Tab -->
            @if(auth()->user()->usertype === 'founder')
                <div class="tab-pane fade" id="companies" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                <h5 class="mb-2 mb-md-0">
                                    <i class="fas fa-building text-primary me-2"></i>Deleted Companies
                                </h5>
                                <div class="text-muted small">
                                    Showing {{ $deletedCompanies->count() }} item{{ $deletedCompanies->count() !== 1 ? 's' : '' }}
                                </div>
                            </div>
                        </div>

                        @if($deletedCompanies->isEmpty())
                            <div class="card-body">
                                <div class="alert alert-light border text-center py-4">
                                    <i class="fas fa-building-circle-xmark fa-2x text-muted mb-3"></i>
                                    <h5 class="text-muted">No deleted companies found</h5>
                                    <p class="mb-0">Deleted companies will appear here</p>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Company Name</th>
                                        <th>Plan</th>
                                        <th width="160">Deleted</th>
                                        <th width="120" class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deletedCompanies as $company)
                                        <tr>
                                            <td class="text-muted align-middle">#{{ $company->id }}</td>
                                            <td class="align-middle fw-medium">{{ $company->company_name }}</td>
                                            <td class="align-middle">{{ $company->plan->name }}</td>
                                            <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $company->deleted_at->diffForHumans() }}
                                            </span>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('softdelete.restore.company', $company->id) }}"
                                                       class="btn btn-outline-success rounded-start-2"
                                                       data-bs-toggle="tooltip"
                                                       title="Restore">
                                                        <i class="fas fa-trash-restore"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('softdelete.forceDelete.company', $company->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-outline-danger rounded-end-2"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Permanently"
                                                                onclick="return confirm('Permanently delete {{ $company->company_name }}?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($deletedCompanies->hasPages())
                                <div class="card-footer bg-white">
                                    {{ $deletedCompanies->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Plans Tab -->
                <div class="tab-pane fade" id="plans" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                <h5 class="mb-2 mb-md-0">
                                    <i class="fas fa-clipboard-list text-primary me-2"></i>Deleted Plans
                                </h5>
                                <div class="text-muted small">
                                    Showing {{ $deletedPlans->count() }} item{{ $deletedPlans->count() !== 1 ? 's' : '' }}
                                </div>
                            </div>
                        </div>

                        @if($deletedPlans->isEmpty())
                            <div class="card-body">
                                <div class="alert alert-light border text-center py-4">
                                    <i class="fas fa-clipboard fa-2x text-muted mb-3"></i>
                                    <h5 class="text-muted">No deleted plans found</h5>
                                    <p class="mb-0">Deleted plans will appear here</p>
                                </div>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                    <tr>
                                        <th width="80">ID</th>
                                        <th>Plan Name</th>
                                        <th>Price</th>
                                        <th>Duration</th>
                                        <th width="160">Deleted</th>
                                        <th width="120" class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($deletedPlans as $plan)
                                        <tr>
                                            <td class="text-muted align-middle">#{{ $plan->id }}</td>
                                            <td class="align-middle fw-medium">{{ $plan->name }}</td>
                                            <td class="align-middle">{{ config('settings.currency_symbol') }}{{ $plan->amount }}</td>
                                            <td class="align-middle">{{ $plan->days }} days</td>
                                            <td class="align-middle">
                                            <span class="badge bg-light text-dark">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $plan->deleted_at->diffForHumans() }}
                                            </span>
                                            </td>
                                            <td class="align-middle text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('softdelete.restore.plan', $plan->id) }}"
                                                       class="btn btn-outline-success rounded-start-2"
                                                       data-bs-toggle="tooltip"
                                                       title="Restore">
                                                        <i class="fas fa-trash-restore"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('softdelete.forceDelete.plan', $plan->id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-outline-danger rounded-end-2"
                                                                data-bs-toggle="tooltip"
                                                                title="Delete Permanently"
                                                                onclick="return confirm('Permanently delete {{ $plan->name }} plan?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($deletedPlans->hasPages())
                                <div class="card-footer bg-white">
                                    {{ $deletedPlans->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <style>
            /* General Container */
        .container-fluid {
            padding: 1.5rem; /* Adjusted padding for main container */
            margin: 0; /* Ensure no unwanted margins */
        }

        /* Header Section */
        h1.h3 {
            font-size: 1.5rem; /* Reduced font size */
            margin-bottom: 0.5rem; /* Adjusted margin */
            padding: 0.25rem 0; /* Added padding for alignment */
        }

        .text-muted {
            font-size: 0.85rem; /* Reduced font size */
            margin: 0.25rem 0; /* Adjusted margin */
        }

        /* Alerts */
        .alert {
            font-size: 0.9rem; /* Reduced font size */
            padding: 0.75rem 1rem; /* Adjusted padding */
            margin: 1rem 0; /* Adjusted margin */
            border-radius: 0.375rem; /* Slightly rounded corners */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        .alert .btn-close {
            padding: 0.75rem; /* Adjusted padding for close button */
        }

        /* Tab Navigation */
        #recycleBinTabs {
            scrollbar-width: none; /* Firefox */
            margin-bottom: 1rem; /* Added margin for spacing */
        }

        #recycleBinTabs::-webkit-scrollbar {
            display: none; /* Chrome/Safari */
        }

        .nav-pills .nav-link {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem; /* Adjusted padding */
            margin: 0.25rem; /* Added margin for spacing */
            font-size: 0.9rem; /* Reduced font size */
            color: #6c757d;
            border: 1px solid transparent;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); /* Subtle shadow */
        }

        .nav-pills .nav-link.active {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            border-color: rgba(13, 110, 253, 0.2);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Enhanced shadow for active tab */
        }

        .nav-pills .nav-link:hover:not(.active) {
            background-color: #f8f9fa;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08); /* Shadow on hover */
        }

        .badge.bg-primary {
            font-size: 0.75rem; /* Reduced font size */
            padding: 0.25rem 0.5rem; /* Adjusted padding */
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.5rem; /* Rounded corners */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Enhanced shadow */
            margin-bottom: 1.5rem; /* Adjusted margin */
        }

        .card-header {
            padding: 1rem 1.5rem; /* Adjusted padding */
            margin-bottom: 0.5rem; /* Added margin for spacing */
            border-bottom: 1px solid #e9ecef; /* Subtle border */
        }

        .card-body {
            padding: 1.5rem; /* Adjusted padding */
        }

        .card-footer {
            padding: 1rem 1.5rem; /* Adjusted padding */
            background-color: transparent; /* Ensure no background */
        }

        /* Table Styles */
        .table {
            font-size: 0.9rem; /* Reduced font size */
            margin-bottom: 0; /* Remove default margin */
        }

        .table thead th {
            font-size: 0.75rem; /* Reduced font size */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            padding: 0.75rem 1rem; /* Adjusted padding */
            border-bottom: 1px solid #dee2e6; /* Subtle border */
        }

        .table tbody td {
            padding: 0.75rem 1rem; /* Adjusted padding */
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0; /* Subtle row separator */
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.03); /* Subtle hover effect */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); /* Row shadow on hover */
        }

        /* Avatar */
        .avatar-sm {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%; /* Ensure circular shape */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Buttons */
        .btn-group-sm .btn {
            font-size: 0.85rem; /* Reduced font size */
            padding: 0.25rem 0.5rem; /* Adjusted padding */
            border-radius: 0.25rem; /* Consistent rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
            margin-right: 0.25rem; /* Margin to separate buttons */
        }

        .btn-group-sm .btn:last-child {
            margin-right: 0; /* Remove margin for the last button */
        }

        .btn-outline-success:hover,
        .btn-outline-danger:hover {
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15); /* Enhanced shadow on hover */
        }

        /* Ensure consistent button shapes */
        .btn-group-sm .btn {
            border-radius: 0.25rem !important; /* Override custom radius classes */
        }

        /* Badges */
        .badge.bg-light {
            font-size: 0.8rem; /* Reduced font size */
            padding: 0.25rem 0.5rem; /* Adjusted padding */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Subtle shadow */
        }

        /* Empty State Alerts */
        .alert-light {
            font-size: 0.9rem; /* Reduced font size */
            padding: 1.5rem; /* Adjusted padding */
            margin: 1rem; /* Adjusted margin */
            border-radius: 0.375rem; /* Rounded corners */
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        /* Pagination */
        .pagination {
            margin: 1rem 0; /* Adjusted margin */
            font-size: 0.85rem; /* Reduced font size */
        }

        .page-link {
            padding: 0.25rem 0.75rem; /* Adjusted padding */
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Subtle shadow */
        }

        .page-link:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Enhanced shadow on hover */
        }

        /* Responsive Adjustments */
        @media (max-width: 767.98px) {
            .container-fluid {
                padding: 1rem; /* Reduced padding for smaller screens */
            }

            .table-responsive {
                border: 0;
            }

            .table thead {
                display: none;
            }

            .table tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.25rem;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05); /* Subtle shadow for mobile cards */
            }

            .table td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem;
                border-bottom: 1px solid #f0f0f0;
            }

            .table td:before {
                content: attr(data-label);
                font-weight: 600;
                margin-right: 1rem;
                color: #6c757d;
                font-size: 0.85rem; /* Reduced font size */
            }

            .table td:last-child {
                border-bottom: 0;
            }

            .nav-pills .nav-link {
                font-size: 0.85rem; /* Further reduced font size for mobile */
                padding: 0.4rem 0.8rem; /* Adjusted padding */
            }

            .btn-group-sm .btn {
                margin-bottom: 0.25rem; /* Added margin for mobile stacking */
                margin-right: 0.25rem; /* Maintain horizontal margin */
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Activate the correct tab based on URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const activeTab = urlParams.get('tab') || 'users';
            const tabElement = document.querySelector(`#${activeTab}-tab`);
            if (tabElement) {
                new bootstrap.Tab(tabElement).show();
            }

            // Make tables responsive by adding data-labels
            function makeTablesResponsive() {
                if (window.innerWidth < 768) {
                    document.querySelectorAll('.table thead th').forEach((th, index) => {
                        const label = th.textContent.trim();
                        document.querySelectorAll(`.table tbody td:nth-child(${index + 1})`).forEach(td => {
                            td.setAttribute('data-label', label);
                        });
                    });
                }
            }

            // Run initially and on window resize
            makeTablesResponsive();
            window.addEventListener('resize', makeTablesResponsive);
        });
    </script>
@endsection
