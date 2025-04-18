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
                            <td>{{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('M d') }}</td>
                            <td>
                                <form method="POST" action="{{ route('employee.updateStatus', $service->id) }}" class="status-form d-flex gap-2">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm status-select">
                                        <option value="Pending" {{ $service->service_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Progress" {{ $service->service_status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Completed" {{ $service->service_status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="Cancelled" {{ $service->service_status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                        class="btn btn-sm btn-info openNotesModal"
                                        data-id="{{ $service->id }}"
                                        data-employee-remarks="{{ $service->employee_remarks }}"
                                        data-technician-notes="{{ $service->technician_notes }}"
                                        data-status="{{ $service->service_status }}">
                                    <i class="bi bi-journal-text me-1"></i> Notes
                                </button>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-primary update-btn">
                                    <i class="bi bi-arrow-repeat me-1"></i> Update
                                </button>
                                </form>
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

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3" id="pagination-container">
                @if($assignedServices instanceof \Illuminate\Pagination\AbstractPaginator)
                    {{ $assignedServices->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>

    <!-- Notes Modal -->
    <div class="modal fade" id="notesModal" tabindex="-1" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="notesForm">
                @csrf
                <input type="hidden" name="service_id" id="modalServiceId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Service Notes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Alert shown when editing is not allowed -->
                        <div id="editAlert" class="alert alert-warning d-none">
                            You can't edit the notes because the status is Completed or Cancelled.
                        </div>

                        <!-- Flash Messages -->
                        <div id="modalSuccessMessage" class="alert alert-success d-none">
                            Notes updated successfully!
                        </div>
                        <div id="modalErrorMessage" class="alert alert-danger d-none">
                            Failed to update notes. Please try again.
                        </div>

                        <div class="mb-3">
                            <label for="employeeRemarks" class="form-label">Employee Remarks</label>
                            <textarea class="form-control" id="employeeRemarks" name="employee_remarks" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="technicianNotes" class="form-label">Technician Notes</label>
                            <textarea class="form-control" id="technicianNotes" name="technician_notes" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" id="saveNotesBtn" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> <!-- only one close button -->
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>







        const notesModal = new bootstrap.Modal(document.getElementById('notesModal'));

        document.querySelectorAll('.openNotesModal').forEach(button => {
            button.addEventListener('click', () => {
                const serviceId = button.dataset.id;
                const employeeRemarks = button.dataset.employeeRemarks || '';
                const technicianNotes = button.dataset.technicianNotes || '';
                const status = button.dataset.status;

                document.getElementById('modalServiceId').value = serviceId;
                document.getElementById('employeeRemarks').value = employeeRemarks;
                document.getElementById('technicianNotes').value = technicianNotes;

                const isEditable = !(status === 'Completed' || status === 'Cancelled');

                // Toggle editability
                document.getElementById('employeeRemarks').readOnly = !isEditable;
                document.getElementById('technicianNotes').readOnly = !isEditable;
                document.getElementById('saveNotesBtn').disabled = !isEditable;

                // Toggle warning message
                const alertBox = document.getElementById('editAlert');
                if (!isEditable) {
                    alertBox.classList.remove('d-none');
                } else {
                    alertBox.classList.add('d-none');
                }

                notesModal.show();
            });
        });

        // New JavaScript (for saving the form and displaying success/failure messages)
        document.getElementById('notesForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route("employee.updateNotes") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    const modalBody = document.querySelector('#notesModal .modal-body');

                    // Clear any previous alert messages if modalBody exists
                    if (modalBody) {
                        modalBody.querySelectorAll('.alert').forEach(alert => alert.remove());
                    }

                    if (data.success) {
                        // If success, hide the modal and show success message
                        notesModal.hide();
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Refresh to update notes
                        });
                    } else {
                        // If failure, show the error message returned by backend
                        if (modalBody) {
                            modalBody.insertAdjacentHTML('beforeend', `
                        <div class="alert alert-danger mt-3">
                            ${data.message}
                        </div>
                    `);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error during fetch:', error);
                    const modalBody = document.querySelector('#notesModal .modal-body');
                    if (modalBody) {
                        modalBody.insertAdjacentHTML('beforeend', `
                    <div class="alert alert-danger mt-3">
                        An error occurred while saving notes.
                    </div>
                `);
                    }
                });
        });




        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function () {
            const input = this.value.toLowerCase();
            const rows = document.querySelectorAll('#serviceTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });
    </script>

    <style>
        .status-select {
            min-width: 120px;
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }

        .update-btn {
            min-width: 90px;
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
        }

        .text-truncate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .btn-info {
            color: white;
            font-size: 0.85rem;
            padding: 0.25rem 0.75rem;
        }

        .card {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin: 1.5rem;
            border-radius: 0.5rem;
        }

        .card-header,
        .card-body {
            padding: 1.5rem;
        }
    </style>
@endsection
