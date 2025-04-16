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

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="bi bi-exclamation-circle-fill me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Plan</th>
                    <th>Plan Amount</th>
                    <th>Discount</th>
                    <th>Final Price</th>
                    <th>Subscription Period</th>
                    <th>Status</th>
                    <th>Company Key</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>
                @foreach($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->company_name }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->contact_number }}</td>

                        <!-- Plan Information -->
                        <td>
                           <span class="badge bg-primary">
                    {{ $company->plan?->name }}
                            </span>
                        </td>
                        <td>₹{{ number_format($company->plan_amount, 2) }}</td>
                        <td class="text-danger">-₹{{ number_format($company->discount, 2) }}</td>
                        <td class="text-success fw-bold">₹{{ number_format($company->final_price, 2) }}</td>

                        <!-- Subscription Period -->
                        <td>
                            {{ $company->subscription_start_date->format('d M Y') }}<br>
                            to<br>
                            {{ $company->subscription_end_date->format('d M Y') }}
                        </td>

                        <!-- Status Form -->
                        <td>
                            <form action="{{ route('companies.update-status', $company->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                                    <option value="active" {{ $company->status === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="expired" {{ $company->status === 'expired' ? 'selected' : '' }}>Expired</option>
                                </select>
                            </form>
                        </td>

                        <!-- Company Key -->
                        <td>
                            <code>{{ $company->company_key ?? 'N/A' }}</code>
                        </td>

                        <!-- Actions -->
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="bi bi-pencil-square">Edit</i>
                                </a>
                                <!-- Trigger button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $company->id }}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>

                            </div>
                        </td>
                    </tr>


                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal{{ $company->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $company->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content rounded-3 shadow">
                                <div class="modal-header bg-warning text-dark">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $company->id }}">Confirm Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete <strong>{{ $company->company_name }}</strong>?<br>
                                    <span class="text-danger">This will remove all related data permanently.</span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('companies.destroy', $company->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                @endforeach
                </tbody>
            </table>


                  </div>
    </div>

@endsection
