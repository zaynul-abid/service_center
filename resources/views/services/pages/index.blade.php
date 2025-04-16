@extends('backend.layouts.app')

@section('title', 'Service Management')

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
    <div class="container-fluid p-2 p-md-3">
        <div class="card border-0 bg-white rounded-lg shadow-xs">
            <div class="card-header bg-transparent border-0 p-2 pb-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                    <h4 class="mb-2 mb-md-0 text-gray-900 fw-semibold">Service Bookings</h4>
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <!-- Search and Filter Form -->
                        <form id="filterForm" class="mb-2 mb-md-0 w-100">
                            <div class="row g-2">
                                <div class="col-12 col-md-4">
                                    <div class="input-group">
                                        <input type="text" name="search" id="searchInput" class="form-control"
                                               placeholder="Search bookings..." value="{{ request('search') }}">
                                        <button type="button" id="searchBtn" class="btn btn-primary">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="date" name="from_date" id="fromDate" class="form-control"
                                           value="{{ request('from_date') }}" placeholder="From date">
                                </div>
                                <div class="col-6 col-md-3">
                                    <input type="date" name="to_date" id="toDate" class="form-control"
                                           value="{{ request('to_date') }}" placeholder="To date">
                                </div>
                                <div class="col-12 col-md-2">
                                    <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                        <a href="{{ route('services.create') }}" class="btn btn-primary px-3 py-2 rounded-md" style="white-space: nowrap;">
                            <i class="bi bi-plus-lg me-2"></i>New Booking
                        </a>
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

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>

            <div class="card-body p-2 p-md-4">
                <!-- Results Count and Loading Indicator -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="resultsCount" class="text-muted small">
                        Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} entries
                    </div>
                    <div id="loadingIndicator" class="spinner-border text-primary" role="status" style="display: none;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div class="table-responsive">
                    <!-- Desktop table -->
                    <div class="d-none d-md-block">
                        <table class="table table-hover align-middle mb-0 w-100" id="serviceTable">
                            <thead>
                            <tr class="text-gray-700">
                                <th class="ps-3 border-end">Booking ID</th>
                                <th class="border-end">Date</th>
                                <th class="border-end">Customer</th>
                                <th class="border-end">Vehicle</th>
                                <th class="border-end">Contact</th>
                                <th class="border-end">Delivery</th>
                                <th class="border-end">Status</th>
                                <th class="border-end">Cost</th>
                                <th class="border-end">images</th>
                                <th class="pe-3 text-end">Actions</th>
                            </tr>
                            </thead>
                            <tbody id="serviceTableBody">
                            @include('services.partials.service_rows', ['services' => $services])
                            </tbody>
                        </table>

                        <!-- Desktop Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {!! $services->appends(request()->query())->links('vendor.pagination.bootstrap-4', [
                                'previousPageText' => '<i class="bi bi-chevron-left"></i>',
                                'nextPageText' => '<i class="bi bi-chevron-right"></i>'
                            ]) !!}
                        </div>
                    </div>

                    <!-- Mobile cards -->
                    <div class="d-block d-md-none">
                        <div id="mobileServiceCards">
                            @include('services.partials.mobile_service_cards', ['services' => $services])
                        </div>

                        <!-- Mobile Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {!! $services->appends(request()->query())->links('vendor.pagination.bootstrap-4', [
                                'previousPageText' => '<i class="bi bi-chevron-left"></i>',
                                'nextPageText' => '<i class="bi bi-chevron-right"></i>'
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-primary">Service Images</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="carousel-inner">
                            <!-- Images will be loaded here dynamically -->
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Debounce function to limit how often a function is called
            function debounce(func, wait, immediate) {
                var timeout;
                return function() {
                    var context = this, args = arguments;
                    var later = function() {
                        timeout = null;
                        if (!immediate) func.apply(context, args);
                    };
                    var callNow = immediate && !timeout;
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                    if (callNow) func.apply(context, args);
                };
            }

            // Function to load services via AJAX
            function loadServices(page = 1) {
                $('#loadingIndicator').show();

                var formData = $('#filterForm').serialize();
                var url = "{{ route('services.index') }}?page=" + page + "&" + formData;

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        if (response.html) {
                            // For non-AJAX fallback
                            $('#serviceTableBody').html($(response.html).find('#serviceTableBody').html());
                            $('#mobileServiceCards').html($(response.html).find('#mobileServiceCards').html());
                            $('.pagination').html($(response.html).find('.pagination').html());
                            $('#resultsCount').html($(response.html).find('#resultsCount').html());
                        } else {
                            // AJAX response
                            $('#serviceTableBody').html(response.desktop_view);
                            $('#mobileServiceCards').html(response.mobile_view);
                            $('#resultsCount').html('Showing ' + response.from + ' to ' + response.to + ' of ' + response.total + ' entries');
                            $('.pagination').html(response.pagination);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while fetching data.');
                    },
                    complete: function() {
                        $('#loadingIndicator').hide();
                    }
                });
            }

            // Search input with debounce
            $('#searchInput').on('keyup', debounce(function() {
                loadServices();
            }, 500));

            // Date filter change
            $('#fromDate, #toDate').on('change', function() {
                loadServices();
            });

            // Search button click
            $('#searchBtn').on('click', function() {
                loadServices();
            });

            // Reset button
            $('#resetBtn').on('click', function() {
                $('#filterForm')[0].reset();
                loadServices();
            });

            // Handle pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                loadServices(page);
            });

            // Handle View Images button click
            $(document).on('click', '.view-images-btn', function() {
                var serviceId = $(this).data('service-id');
                $('#loadingIndicator').show();

                $.ajax({
                    url: "{{ route('services.getImages', ':id') }}".replace(':id', serviceId),
                    type: 'GET',
                    success: function(response) {
                        var carouselInner = $('#carousel-inner');
                        carouselInner.empty();

                        if (response.images && response.images.length > 0) {
                            response.images.forEach(function(image, index) {
                                var activeClass = index === 0 ? 'active' : '';
                                carouselInner.append(`
                            <div class="carousel-item ${activeClass}">
                                <img src="${image.url}" class="d-block w-100 rounded-3" alt="Service image">
<!--                                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded-pill">-->
                                    <p class="mb-0 mt-1">IMAGE ${index + 1} of ${response.images.length}</p>
                                </div>
                            </div>
                        `);
                            });

                            // Initialize/reinitialize the carousel
                            $('#imageCarousel').carousel();
                            $('#imageModal').modal('show');
                        } else {
                            alert('No images found for this service.');
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        alert('An error occurred while loading images.');
                    },
                    complete: function() {
                        $('#loadingIndicator').hide();
                    }
                });
            });

            // Handle individual image clicks (if you're keeping this functionality)
            window.showImage = function(src) {
                // If you want to keep the simple image modal as fallback
                document.getElementById('largeImage').src = src;
                $('#imageModal').modal('show');
            };
        });
    </script>
    <style>
        /* Responsive form styles */
        @media (max-width: 767.98px) {
            #filterForm .col-md-4 {
                margin-bottom: 0.5rem;
            }
            #filterForm .col-6 {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
        }

        /* Loading spinner alignment */
        #loadingIndicator {
            width: 1.5rem;
            height: 1.5rem;
        }

        /* Status badge styles */
        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1) !important;
        }
        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }
        .bg-info-light {
            background-color: rgba(23, 162, 184, 0.1) !important;
        }
        .bg-secondary-light {
            background-color: rgba(108, 117, 125, 0.1) !important;
        }


        /* Mobile card styles */
        @media (max-width: 767.98px) {
            .service-card {
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                margin-bottom: 1rem;
            }
            .service-card .btn {
                padding: 0.375rem 0.75rem;
            }
        }
    </style>
@endpush
