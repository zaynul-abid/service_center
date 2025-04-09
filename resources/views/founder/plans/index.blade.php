@extends('backend.layouts.app')

@section('title', 'Plan Management')

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')
    <div class="container-fluid px-4 py-5 bg-light min-vh-100">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h4 fw-bold text-dark">Plan Management</h1>
                    <a href="{{ route('plans.create') }}" class="btn btn-primary btn-sm shadow-sm">
                        <i class="fas fa-plus me-1"></i> Create Plan
                    </a>
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


                <!-- Plans Table -->
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="py-3 px-4">Plan Name</th>
                                    <th class="py-3 px-4">Amount</th>
                                    <th class="py-3 px-4">Duration</th>
                                    <th class="py-3 px-4">Status</th>
                                    <th class="py-3 px-4 text-end">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($plans as $plan)
                                    <tr class="transition-all">
                                        <td class="py-3 px-4 fw-medium">{{ $plan->name }}</td>
                                        <td class="py-3 px-4">₹{{ number_format($plan->amount, 2) }}</td>
                                        <td class="py-3 px-4">{{ $plan->days }} days</td>
                                        <td class="py-3 px-4">
                                            <span class="badge {{ $plan->status ? 'bg-success' : 'bg-secondary' }} rounded-pill">
                                                {{ $plan->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('plans.edit', $plan->id) }}"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>
                                                <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this plan?')">
                                                        <i class="fas fa-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-5 text-center text-muted">
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <i class="fas fa-info-circle fs-5"></i>
                                                <span>No plans found.</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($plans->hasPages())
                        <div class="card-footer bg-light border-top-0 py-3">
                            {{ $plans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Modern custom styles */
        .card {
            transition: box-shadow 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .table tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }

        .btn-outline-primary, .btn-outline-danger {
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .btn-outline-danger:hover {
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.2);
        }

        .badge {
            padding: 0.5em 0.9em;
            font-weight: 500;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .table thead {
                display: none;
            }

            .table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                background: #fff;
            }

            .table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid #dee2e6;
            }

            .table tbody td:before {
                content: attr(data-label);
                font-weight: 600;
                color: #495057;
                margin-right: 1rem;
            }

            .table tbody td:last-child {
                border-bottom: none;
                justify-content: flex-end;
            }

            .d-flex.justify-content-end {
                flex-wrap: wrap;
                gap: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const headers = document.querySelectorAll('thead tr th');
            const rows = document.querySelectorAll('tbody tr');

            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                cells.forEach((cell, index) => {
                    if (index < headers.length) {
                        cell.setAttribute('data-label', headers[index].textContent);
                    }
                });
            });
        });
    </script>
@endsection
