@extends('backend.layouts.app')

@section('title', 'Assign Enquiry to Employees')

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
    <div class="container-fluid py-3">
        <div class="card shadow-sm rounded-2">
            <div class="card-header bg-white py-3 px-4">
                <h5 class="mb-0">Assign Enquiry to Employees</h5>
            </div>

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card-body p-3">
                <!-- Filter Section -->
                <div class="row mb-3 g-2">
                    <div class="col-md-3">
                        <select id="status-filter" class="form-select form-select-sm">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="in_progress">In Progress</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="Unassigned">Unassigned</option>
                        </select>
                    </div>
                    <div class="row g-1 align-items-center" style="max-width: 320px;">
                        <div class="col">
                            <input type="date" id="from-date-filter" class="form-control form-control-sm" placeholder="From">
                        </div>
                        <div class="col-auto">
                            <span class="form-text">to</span>
                        </div>
                        <div class="col">
                            <input type="date" id="to-date-filter" class="form-control form-control-sm" placeholder="To">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <input type="text" id="search-input" class="form-control form-control-sm" placeholder="Search...">
                    </div>
                    <div class="col-md-3">
                        <button id="reset-filters" class="btn btn-sm btn-outline-secondary">Reset Filters</button>
                    </div>
                </div>

                <form action="{{ route('store.assign') }}" method="POST" id="assign-form">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="bg-light">
                            <tr>
                                <th width="40px" class="ps-2 pe-1 py-2">
                                    <div class="form-check">
                                        <input class="form-check-input m-0" type="checkbox" id="select-all">
                                    </div>
                                </th>
                                <th class="px-2 py-2">ID</th>
                                <th class="px-2 py-2">Date/Time</th>
                                <th class="px-2 py-2">Customer</th>
                                <th class="px-2 py-2">Contact</th>
                                <th class="px-2 py-2">Status</th>
                                <th class="px-2 py-2">Vehicle</th>
                                <th class="px-2 py-2">Employee</th>
                            </tr>
                            </thead>
                            <tbody id="services-table-body">
                            @include('assign.table_rows', ['services' => $services])
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3" id="pagination-container">
                        @if($services instanceof \Illuminate\Pagination\AbstractPaginator)
                            {{ $services->links('pagination::bootstrap-5') }}
                        @endif
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <select name="employee_id" class="form-select form-select-sm" required>
                                    <option value="" disabled selected>Select Employee</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button type="submit" class="btn btn-sm btn-primary px-3">
                                    Assign Selected
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>


        $(document).ready(function() {
            // Function to apply filters
            function applyFilters() {
                const status = $('#status-filter').val();
                const fromDate = $('#from-date-filter').val();
                const toDate = $('#to-date-filter').val();
                const search = $('#search-input').val();

                // Log the data being sent
                console.log('Sending filter data:', {
                    status: status,
                    from_date: fromDate,
                    to_date: toDate,
                    search: search
                });

                showLoading();

                $.ajax({
                    url: "{{ route('show.assign') }}", // Replace with your actual route
                    type: "GET",
                    data: {
                        status: status,
                        from_date: fromDate,
                        to_date: toDate,
                        search: search,
                        ajax: true
                    },
                    success: function(response) {
                        // Log the response for debugging
                        console.log('Response received:', response);

                        $('#services-table-body').html(response.html);
                        $('#pagination-container').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        alert('An error occurred while filtering data.');
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            }

            // Handle filter changes
            $('#status-filter, #from-date-filter, #to-date-filter').on('change', function() {
                applyFilters();
            });

            // Search input with debounce
            let searchTimer;
            $('#search-input').on('keyup', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    applyFilters();
                }, 500); // 500ms debounce time
            });

            // Reset filters
            $('#reset-filters').on('click', function() {
                $('#status-filter').val('');
                $('#from-date-filter').val('');
                $('#to-date-filter').val('');
                $('#search-input').val('');
                applyFilters();
            });

            // Handle pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                showLoading();

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        ajax: true,
                        status: $('#status-filter').val(),
                        from_date: $('#from-date-filter').val(),
                        to_date: $('#to-date-filter').val(),
                        search: $('#search-input').val()
                    },
                    success: function(response) {
                        $('#services-table-body').html(response.html);
                        $('#pagination-container').html(response.pagination);
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr.responseText);
                        alert('An error occurred while loading the page.');
                    },
                    complete: function() {
                        hideLoading();
                    }
                });
            });

            // Loading indicator functions
            function showLoading() {
                $('#services-table-body').html('<tr><td colspan="8" class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');
            }

            function hideLoading() {
                // Handled by AJAX success/complete
            }
        });


    </script>

@endsection

