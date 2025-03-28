@extends('registration-form.layouts.app')
@section('title','create')

@section('content')
<div class="container">
{{--    <div class="mt-4">--}}
{{--        <button--}}
{{--            type="button"--}}
{{--            class="btn btn-outline-dark rounded-pill px-4 py-2 fw-medium"--}}
{{--            onclick="history.back()"--}}
{{--            style="--}}
{{--            transition: all 0.3s ease;--}}
{{--            border-width: 2px;--}}
{{--        "--}}
{{--            onmouseover="this.style.backgroundColor='#111111'; this.style.color='white'"--}}
{{--            onmouseout="this.style.backgroundColor='transparent'; this.style.color='#1a1a1a'"--}}
{{--        >--}}
{{--            <i class="bi bi-arrow-left me-2"></i>Back--}}
{{--        </button>--}}
{{--    </div>--}}

    <h2>Create Vehicle Service</h2>

    <!-- Display Current Date and Time -->
    <div class="current-time">
        <strong>Date:</strong> <span id="currentDateTime"></span>
    </div>

    <!-- Header Image -->
    <div class="header-image">
        <img src="{{ asset('8959247.jpg') }}" alt="Vehicle Service Center">
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Message -->
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form -->
    <form id="serviceForm" action="{{ route('services.store') }}" method="POST" enctype="multipart/form-data"  autocomplete="off">
        @csrf

        <!-- Booking Date and Time -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="booking_date" class="form-label required">Booking Date</label>
                <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="booking_date" name="booking_date" value="{{ old('booking_date') }}" required>
                @error('booking_date')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="booking_time" class="form-label required">Booking Time</label>
                <input type="time" class="form-control @error('booking_time') is-invalid @enderror" id="booking_time" name="booking_time" value="{{ old('booking_time') }}" required>
                @error('booking_time')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="reference_number" class="form-label">Reference Number</label>
                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" id="reference_number" name="reference_number" placeholder="Enter Reference Number" value="{{ old('reference_number') }}">
                @error('reference_number')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            @if(auth()->user()->usertype === 'founder')
            <div class="col-md-6">
                <label for="company_id" class="form-label required">Select Company</label>
                <select class="form-control @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                    <option value="" disabled selected>Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                @error('company_id')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            @endif
        </div>

        <h6 class="mt-4">VEHICLE DETAILS</h6>
        <hr>

        <!-- Vehicle Details -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vehicleNumber" class="form-label required">Vehicle Number</label>
                <input type="text" class="form-control" id="vehicleNumber" name="vehicle_number" placeholder="Enter Vehicle Number" required>

                <ul id="vehicleSuggestions" style="display:none; list-style:none; padding-left:0; border:1px solid #ccc;">
                    <!-- Suggestions will be shown here dynamically -->
                </ul>
            </div>
            <div class="col-md-6">
                <label for="vehicle_type" class="form-label required">Vehicle Type</label>
                <select class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" required>
                    <option value="" disabled selected>Select Vehicle Type</option>
                    <option value="two wheeler" {{ old('vehicle_type') == 'two wheeler' ? 'selected' : '' }}>Two Wheeler</option>
                    <option value="three wheeler" {{ old('vehicle_type') == 'three wheeler' ? 'selected' : '' }}>Three Wheeler</option>
                    <option value="four wheeler" {{ old('vehicle_type') == 'four wheeler' ? 'selected' : '' }}>Four Wheeler</option>
                    <option value="heavy" {{ old('vehicle_type') == 'heavy' ? 'selected' : '' }}>Heavy</option>
                    <option value="other" {{ old('vehicle_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('fuel_type')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">

            <div class="col-md-6" style="position: relative;">
                <label for="vehicleCompany" class="form-label required">Vehicle Company</label>
                <input type="text" class="form-control" id="vehicleCompany" name="vehicle_company" placeholder="Enter Vehicle Company" required>

                <!-- Suggestions List -->
                <ul id="companySuggestions" style="display:none; list-style:none; padding-left:0;  border:1px solid #ccc;">
                    <!-- Dynamically added suggestions will have the same width as the input -->
                </ul>
            </div>



            <div class="col-md-6" style="position: relative;">
                <label for="vehicleModel" class="form-label required">Vehicle Model</label>
                <input type="text" class="form-control" id="vehicleModel" name="vehicle_model" placeholder="Enter Vehicle Model" required>

                <!-- Suggestions List -->
                <ul id="modelSuggestions" style="display:none; list-style:none; padding-left:0; border:1px solid #ccc;">
                    <!-- Model suggestions will be dynamically inserted here -->
                </ul>
            </div>
        </div>

        <div class="row mb-3">

            <div class="col-md-4">
                <label for="fuel_type" class="form-label required">Fuel Type</label>
                <select class="form-control @error('fuel_type') is-invalid @enderror" id="fuel_type" name="fuel_type" required>
                    <option value="" disabled selected>Select Fuel Type</option>
                    <option value="petrol" {{ old('fuel_type') == 'petrol' ? 'selected' : '' }}>Petrol</option>
                    <option value="diesel" {{ old('fuel_type') == 'diesel' ? 'selected' : '' }}>Diesel</option>
                    <option value="electric" {{ old('fuel_type') == 'electric' ? 'selected' : '' }}>Electric</option>
                    <option value="cng" {{ old('fuel_type') == 'cng' ? 'selected' : '' }}>CNG</option>
                    <option value="other" {{ old('fuel_type') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('fuel_type')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="fuel_level" class="form-label">Fuel Level (%)</label>
                <input type="number" class="form-control @error('fuel_level') is-invalid @enderror" id="fuel_level" name="fuel_level" placeholder="Enter Fuel Level" min="0" max="100" step="1" value="{{ old('fuel_level') }}">
                @error('fuel_level')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="km_driven" class="form-label">Kilometers Driven</label>
                <input type="number" class="form-control @error('km_driven') is-invalid @enderror" id="km_driven" name="km_driven" placeholder="Enter Kilometers Driven" min="0" step="1" value="{{ old('km_driven') }}">
                @error('km_driven')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">


        </div>

        <h6 class="mt-4">CUSTOMER DETAILS</h6>
        <hr>

        <!-- Customer Details -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="customerName" class="form-label required">Customer Name</label>
                <input type="text" class="form-control" id="customerName" name="customer_name" placeholder="Enter Customer Name" required>

                <ul id="customerSuggestions" style="display:none; list-style:none; padding-left:0; border:1px solid #ccc;">
                    <!-- Customer suggestions will be shown dynamically here -->
                </ul>
            </div>

            <div class="col-md-6">
                <label for="contactNumber1" class="form-label required">Contact Number 1</label>
                <input type="tel" class="form-control" id="contactNumber1" name="contact_number_1" placeholder="Enter Contact Number 1" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="contactNumber2" class="form-label">Contact Number 2</label>
                <input type="tel" class="form-control" id="contactNumber2" name="contact_number_2" placeholder="Enter Contact Number 2">
            </div>

            <div class="col-md-6">
                <label for="place" class="form-label">Place</label>
                <input type="text" class="form-control" id="place" name="place" placeholder="Enter Place">
            </div>
        </div>


        <h6 class="mt-4">SERVICE DETAILS</h6>
        <hr>

        <!-- Customer Complaint -->
        <div class="mb-3">
            <label for="customerComplaint" class="form-label required">Complaint of Customer</label>
            <textarea class="form-control @error('customerComplaint') is-invalid @enderror" id="customer_complaint" name="customer_complaint" rows="3" placeholder="Enter Customer Complaint" required>{{ old('customerComplaint') }}</textarea>
            @error('customerComplaint')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Service Details -->
        <div class="mb-3">
            <label for="serviceDetails" class="form-label">Service Details</label>
            <textarea class="form-control @error('serviceDetails') is-invalid @enderror" id="serviceDetails" name="service_details" rows="3" placeholder="Enter Service Details">{{ old('serviceDetails') }}</textarea>
            @error('serviceDetails')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remarks -->
        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3" placeholder="Enter Remarks">{{ old('remarks') }}</textarea>
            @error('remarks')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Cost Field -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cost" class="form-label">Cost</label>
                <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" placeholder="Enter Estimated Cost" value="{{ old('cost') }}">
                @error('cost')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="expectedDeliveryDate" class="form-label required">Expected Delivery Date</label>
                <input type="date" class="form-control @error('expectedDeliveryDate') is-invalid @enderror" id="expectedDeliveryDate" name="expected_delivery_date" value="{{ old('expectedDeliveryDate') }}">
                @error('expectedDeliveryDate')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="expectedDeliveryTime" class="form-label ">Expected Delivery Time</label>
                <input type="time" class="form-control @error('expectedDeliveryTime') is-invalid @enderror" id="expectedDeliveryTime" name="expected_delivery_time" value="{{ old('expectedDeliveryTime') }}" >
                @error('expectedDeliveryTime')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Multiple Photo Upload -->
        <div class="mb-3">
            <div class="file-upload">
                <label for="photoUpload">Click to Upload Photos (Multiple Allowed)</label>
                <input type="file" class="form-control @error('photoUpload.*') is-invalid @enderror" id="photoUpload" name="photos[]" multiple accept="image/*">
                @error('photoUpload.*')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit and Refresh Buttons -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary" id="submitButton">Submit</button>
            <button type="button" class="btn btn-secondary" onclick="resetForm()">Refresh</button>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('serviceForm');

        // Get all focusable input elements (exclude buttons)
        const focusableElements = form.querySelectorAll(
            'input:not([type="hidden"]):not([type="submit"]):not([type="button"]), select, textarea'
        );

        const focusableArray = Array.from(focusableElements);
        const lastInputField = focusableArray[focusableArray.length - 1]; // Get the last input field

        // Add event listeners to all focusable elements
        focusableArray.forEach((element, index) => {
            // Enter key moves to next field
            element.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();

                    // Skip navigation if the current element is a textarea (allow Shift+Enter for new lines)
                    if (e.target.tagName === 'TEXTAREA') {
                        return;
                    }

                    // If we're already on the last field, don't move focus
                    if (element === lastInputField) {
                        return;
                    }

                    // Find next focusable element
                    let nextIndex = index + 1;
                    while (nextIndex < focusableArray.length) {
                        const nextElement = focusableArray[nextIndex];
                        if (nextElement.offsetParent !== null && !nextElement.disabled) {
                            nextElement.focus();
                            break;
                        }
                        nextIndex++;
                    }
                }

                // Esc key moves to previous field
                if (e.key === 'Escape') {
                    e.preventDefault();

                    // Find previous focusable element
                    let prevIndex = index - 1;
                    while (prevIndex >= 0) {
                        const prevElement = focusableArray[prevIndex];
                        if (prevElement.offsetParent !== null && !prevElement.disabled) {
                            prevElement.focus();
                            break;
                        }
                        prevIndex--;
                    }
                }
            });
        });

        // Special handling for textareas - allow Shift+Enter for new lines
        const textareas = form.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            textarea.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.shiftKey) {
                    // Allow default behavior (new line)
                    return;
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    // Move to next field
                    const currentIndex = focusableArray.indexOf(textarea);
                    let nextIndex = currentIndex + 1;
                    while (nextIndex < focusableArray.length) {
                        const nextElement = focusableArray[nextIndex];
                        if (nextElement.offsetParent !== null && !nextElement.disabled) {
                            // Don't move to buttons
                            if (nextElement.tagName !== 'BUTTON') {
                                nextElement.focus();
                                break;
                            }
                        }
                        nextIndex++;
                    }
                }
            });
        });
    });
</script>


@endsection
