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
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Contact Number</th>
                    <th>Address</th>
                    <th>Registration Number</th>
                    <th>Plan</th>
                    <th>Subscription Start</th>
                    <th>Subscription End</th>
                    <th>Status</th> <!-- Status Column -->
                    <th>Company Key</th> <!-- Display the Company Key -->
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->company_name }}</td>
                        <td>{{ $company->contact_number }}</td>
                        <td>{{ Str::limit($company->address, 30) }}</td>
                        <td>{{ $company->registration_number }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $company->plan }}</span>
                        </td>
                        <td>{{ $company->subscription_start_date }}</td>
                        <td>{{ $company->subscription_end_date }}</td>

                        <!-- Status Form -->
                        <td>
                            <form action="{{ route('companies.update-status', $company->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()">
                                    <option value="active" {{ $company->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ $company->status === 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </form>
                        </td>

                        <!-- Displaying Company Key -->
                        <td>
                            {{ $company->company_key ?? 'Not Generated' }}
                        </td>

                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
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
