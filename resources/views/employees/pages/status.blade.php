@extends('employees.layouts.app')

@section('title', 'Assigned Services')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Assigned Services</h4>
            <div class="d-flex gap-2">
                <input type="search" class="form-control form-control-sm" placeholder="Search..." id="searchInput">
            </div>
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
            <div class="table-responsive">
                <table id="serviceTable" class="table table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>Booking #</th>
                        <th>Vehicle</th>
                        <th class="d-none d-md-table-cell">Model</th>
                        <th class="d-none d-lg-table-cell">Customer</th>
                        <th class="d-none d-xl-table-cell">Contact</th>
                        <th class="d-none d-xl-table-cell">Complaint</th>
                        <th>Delivery</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($assignedServices as $service)
                        <tr>
                            <td class="fw-bold">#{{ $service->booking_id }}</td>
                            <td>
                                <span class="d-block">{{ $service->vehicle_number }}</span>
                                <small class="text-muted d-md-none">{{ $service->vehicle_model }}</small>
                            </td>
                            <td class="d-none d-md-table-cell">{{ $service->vehicle_model }}</td>
                            <td class="d-none d-lg-table-cell">{{ $service->customer_name }}</td>
                            <td class="d-none d-xl-table-cell">{{ $service->contact_number_1 }}</td>
                            <td class="d-none d-xl-table-cell">
                                <div class="text-truncate" style="max-width: 200px;"
                                     title="{{ $service->customer_complaint }}">
                                    {{ $service->customer_complaint }}
                                </div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('M d') }}
                            </td>
                            <td>
                                <form method="POST" action="{{ route('employee.updateStatus', $service->id) }}"
                                      class="status-form">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm status-select">
                                        <option
                                            value="Pending" {{ $service->service_status == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option
                                            value="In Progress" {{ $service->service_status == 'In Progress' ? 'selected' : '' }}>
                                            In Progress
                                        </option>
                                        <option
                                            value="Completed" {{ $service->service_status == 'Completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                        <option
                                            value="Cancelled" {{ $service->service_status == 'Cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                    </select>
                            </td>
                            <td class="notes-cell">
                                @if($service->service_status != 'Completed')
                                    <input type="text" name="notes" class="form-control form-control-sm notes-input"
                                           value="{{ $service->notes }}" placeholder="Add notes...">
                                @else
                                    <span class="text-muted">N/A</span>
                                    <input type="hidden" name="notes" value="{{ $service->notes }}">
                                @endif
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary update-btn">
                                    <i class="bi bi-arrow-repeat me-1"></i> Update
                                </button>
                                </form>
                                @if(!empty($service->photos) && is_string($service->photos))
                                    <button class="btn btn-sm btn-info ms-1" data-bs-toggle="modal"
                                            data-bs-target="#photosModal{{ $service->id }}">
                                        <i class="bi bi-images"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            @if($assignedServices->isEmpty())
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    No services assigned to you yet.
                </div>
            @endif
        </div>
    </div>

    <!-- Photos Modals -->
    @foreach($assignedServices as $service)
        @if(!empty($service->photos) && is_string($service->photos))
            <div class="modal fade" id="photosModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Service Photos (#{{ $service->booking_id }})</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                @foreach(json_decode($service->photos, true) as $photo)
                                    <div class="col-md-4 mb-3">
                                        <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded"
                                             alt="Service photo">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const input = this.value.toLowerCase();
            const rows = document.querySelectorAll('#serviceTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });

        // Toggle notes field visibility based on status selection
        document.querySelectorAll('.status-select').forEach(select => {
            // Initialize on page load
            toggleNotesField(select);

            // Add change event listener
            select.addEventListener('change', function () {
                toggleNotesField(this);
            });
        });

        function toggleNotesField(selectElement) {
            const form = selectElement.closest('form');
            const notesCell = form.closest('tr').querySelector('.notes-cell');

            if (selectElement.value === 'Completed') {
                notesCell.innerHTML = `
                    <span class="text-muted">N/A</span>
                    <input type="hidden" name="notes" value="${form.querySelector('.notes-input')?.value || ''}">
                `;
            } else {
                // Only recreate if not already there to preserve any entered notes
                if (!notesCell.querySelector('.notes-input')) {
                    notesCell.innerHTML = `
                        <input type="text" name="notes" class="form-control form-control-sm notes-input"
                               value="${form.querySelector('input[name="notes"]')?.value || ''}" placeholder="Add notes...">
                    `;
                }
            }
        }
    </script>

    <style>
        .status-select {
            min-width: 120px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .status-select:hover {
            border-color: #0d6efd;
        }

        .notes-input {
            width: 100%;
            min-width: 150px;
        }

        .update-btn {
            min-width: 90px;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-info {
            color: white;
        }

        .notes-cell {
            min-width: 150px;
        }
    </style>
@endsection
