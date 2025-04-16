@extends('registration-form.layouts.app')
@section('title', 'Edit')

@section('content')
<div class="container">
    <h2>Edit Vehicle Service</h2>

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
    <form id="serviceForm" action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PUT')
        <input type="hidden" name="company_id" value="{{$service->company_id}}">

        <!-- Booking Date and Time -->

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="bookingDate" class="form-label"> Date</label>
                <input type="date" class="form-control @error('booking_date') is-invalid @enderror" id="bookingDate" name="booking_date"  value="{{ old('booking_date', \Carbon\Carbon::parse($service->booking_date)->format('Y-m-d')) }}">
                @error('booking_date')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="bookingTime" class="form-label">Time</label>
                <input type="time" class="form-control @error('booking_time') is-invalid @enderror" id="bookingTime" name="booking_time" value="{{ old('booking_time', $service->booking_time) }}">
                @error('booking_time')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <div class="row mb-3">
            <div class="col-md-6">
                <label for="reference_number" class="form-label">Reference Number</label>
                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" id="reference_number" name="reference_number" placeholder="Enter Reference Number" value="{{ old('reference_number', $service->reference_number) }}">
                @error('reference_number')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h6 class="mt-4">VEHICLE DETAILS</h6>
        <hr>

        <!-- Vehicle Details -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vehicleNumber" class="form-label required">Vehicle Number</label>
                <input type="text" class="form-control @error('vehicle_number') is-invalid @enderror" id="vehicleNumber" name="vehicle_number" placeholder="Enter Vehicle Number" value="{{ old('vehicle_number', $service->vehicle_number) }}" required>
                @error('vehicle_number')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror

                <ul id="vehicleSuggestions" style="display:none; list-style:none; padding-left:0; border:1px solid #ccc;">
                    <!-- Suggestions will be shown here dynamically -->
                </ul>
            </div>
            <div class="col-md-6">
                <label for="vehicle_type" class="form-label required">Vehicle Type</label>
                <select class="form-control @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" required>
                    <option value="" disabled>Select Vehicle Type</option>
                    <option value="two wheeler" {{ old('vehicle_type', $service->vehicle_type) == 'two wheeler' ? 'selected' : '' }}>Two Wheeler</option>
                    <option value="three wheeler" {{ old('vehicle_type', $service->vehicle_type) == 'three wheeler' ? 'selected' : '' }}>Three Wheeler</option>
                    <option value="four wheeler" {{ old('vehicle_type', $service->vehicle_type) == 'four wheeler' ? 'selected' : '' }}>Four Wheeler</option>
                    <option value="heavy" {{ old('vehicle_type', $service->vehicle_type) == 'heavy' ? 'selected' : '' }}>Heavy</option>
                    <option value="other" {{ old('vehicle_type', $service->vehicle_type) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('vehicle_type')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6" style="position: relative;">
                <label for="vehicleCompany" class="form-label required">Vehicle Company</label>
                <input type="text" class="form-control @error('vehicle_company') is-invalid @enderror" id="vehicleCompany" name="vehicle_company" placeholder="Enter Vehicle Company" value="{{ old('vehicle_company', $service->vehicle_company) }}" required>
                @error('vehicle_company')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror

                <ul id="companySuggestions" style="display:none; list-style:none; padding-left:0;  border:1px solid #ccc;">
                    <!-- Dynamically added suggestions will have the same width as the input -->
                </ul>
            </div>

            <div class="col-md-6" style="position: relative;">
                <label for="vehicleModel" class="form-label required">Vehicle Model</label>
                <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror" id="vehicleModel" name="vehicle_model" placeholder="Enter Vehicle Model" value="{{ old('vehicle_model', $service->vehicle_model) }}" required>
                @error('vehicle_model')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror

                <ul id="modelSuggestions" style="display:none; list-style:none; padding-left:0; border:1px solid #ccc;">
                    <!-- Model suggestions will be dynamically inserted here -->
                </ul>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="fuel_type" class="form-label required">Fuel Type</label>
                <select class="form-control @error('fuel_type') is-invalid @enderror" id="fuel_type" name="fuel_type" required>
                    <option value="" disabled>Select Fuel Type</option>
                    <option value="petrol" {{ old('fuel_type', $service->fuel_type) == 'petrol' ? 'selected' : '' }}>Petrol</option>
                    <option value="diesel" {{ old('fuel_type', $service->fuel_type) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                    <option value="electric" {{ old('fuel_type', $service->fuel_type) == 'electric' ? 'selected' : '' }}>Electric</option>
                    <option value="cng" {{ old('fuel_type', $service->fuel_type) == 'cng' ? 'selected' : '' }}>CNG</option>
                    <option value="other" {{ old('fuel_type', $service->fuel_type) == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('fuel_type')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="fuel_level" class="form-label">Fuel Level (%)</label>
                <input type="number" class="form-control @error('fuel_level') is-invalid @enderror" id="fuel_level" name="fuel_level" placeholder="Enter Fuel Level" min="0" max="100" step="1" value="{{ old('fuel_level', $service->fuel_level) }}">
                @error('fuel_level')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-4">
                <label for="km_driven" class="form-label">Kilometers Driven</label>
                <input type="number" class="form-control @error('km_driven') is-invalid @enderror" id="km_driven" name="km_driven" placeholder="Enter Kilometers Driven" min="0" step="1" value="{{ old('km_driven', $service->km_driven) }}">
                @error('km_driven')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <h6 class="mt-4">CUSTOMER DETAILS</h6>
        <hr>

        <!-- Customer Details -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="customerName" class="form-label required">Customer Name</label>
                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customerName" name="customer_name" placeholder="Enter Customer Name" value="{{ old('customer_name', $service->customer_name) }}" required>
                @error('customer_name')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="contactNumber1" class="form-label required">Contact Number 1</label>
                <input type="tel" class="form-control @error('contact_number_1') is-invalid @enderror" id="contactNumber1" name="contact_number_1" placeholder="Enter Contact Number 1" value="{{ old('contact_number_1', $service->contact_number_1) }}" required>
                @error('contact_number_1')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="contactNumber2" class="form-label">Contact Number 2</label>
                <input type="tel" class="form-control @error('contact_number_2') is-invalid @enderror" id="contactNumber2" name="contact_number_2" placeholder="Enter Contact Number 2" value="{{ old('contact_number_2', $service->contact_number_2) }}">
                @error('contact_number_2')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="place" class="form-label">Place</label>
                <input type="text" class="form-control @error('place') is-invalid @enderror" id="place" name="place" placeholder="Enter Place" value="{{ old('place', $service->place) }}">
                @error('place')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>


        <h6 class="mt-4">SERVICE DETAILS</h6>
        <hr>

        <!-- Customer Complaint -->
        <div class="mb-3">
            <label for="customerComplaint" class="form-label required">Complaint of Customer</label>
            <textarea class="form-control @error('customer_complaint') is-invalid @enderror" id="customer_complaint" name="customer_complaint" rows="3" placeholder="Enter Customer Complaint" required>{{ old('customer_complaint', $service->customer_complaint) }}</textarea>
            @error('customer_complaint')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Service Details -->
        <div class="mb-3">
            <label for="serviceDetails" class="form-label">Service Details</label>
            <textarea class="form-control @error('service_details') is-invalid @enderror" id="serviceDetails" name="service_details" rows="3" placeholder="Enter Service Details">{{ old('service_details', $service->service_details) }}</textarea>
            @error('service_details')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remarks -->
        <div class="mb-3">
            <label for="remarks" class="form-label">Remarks</label>
            <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3" placeholder="Enter Remarks">{{ old('remarks', $service->remarks) }}</textarea>
            @error('remarks')
                <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
            @enderror
        </div>

        <!-- Cost Field -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="cost" class="form-label">Cost</label>
                <input type="number" class="form-control @error('cost') is-invalid @enderror" id="cost" name="cost" placeholder="Enter Estimated Cost" value="{{ old('cost', $service->cost) }}">
                @error('cost')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="expectedDeliveryDate" class="form-label">Expected Delivery Date</label>
                <input type="date" class="form-control @error('expected_delivery_date') is-invalid @enderror" id="expectedDeliveryDate" name="expected_delivery_date"  value="{{ old('expected_delivery_date', \Carbon\Carbon::parse($service->expected_delivery_date)->format('Y-m-d')) }}">
                @error('expected_delivery_date')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-4">
                <label for="expectedDeliveryTime" class="form-label">Expected Delivery Time</label>
                <input type="time" class="form-control @error('expected_delivery_time') is-invalid @enderror" id="expectedDeliveryTime" name="expected_delivery_time" value="{{ old('expected_delivery_time', $service->expected_delivery_time) }}">
                @error('expected_delivery_time')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Status Field -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="status" class="form-label required">Status</label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                    <option value="" disabled>Select Status</option>
                    <option value="pending" {{ old('status', $service->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ old('status', $service->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ old('status', $service->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $service->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Multiple Photo Upload -->
        <div class="mb-3">
            <div class="file-upload">
                <label for="photoUpload">Click to Upload Photos (Multiple Allowed)</label>
                <input type="file" class="form-control @error('photos.*') is-invalid @enderror" id="photoUpload" name="photos[]" multiple accept="image/*">
                @error('photos.*')
                    <div class="text-danger mt-1" style="font-size: 0.9rem;">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Submit and Refresh Buttons -->
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('serviceForm');
        const focusableElements = form.querySelectorAll('input, select, textarea, button');
        const focusableArray = Array.from(focusableElements).filter(el =>
            !el.disabled &&
            el.type !== 'hidden' &&
            el.tabIndex !== -1
        );

        // Function to get current focused element index
        function getCurrentIndex() {
            return focusableArray.indexOf(document.activeElement);
        }

        // Function to focus next element
        function focusNext() {
            const currentIndex = getCurrentIndex();
            if (currentIndex < focusableArray.length - 1) {
                focusableArray[currentIndex + 1].focus();
            }
        }

        // Function to focus previous element
        function focusPrevious() {
            const currentIndex = getCurrentIndex();
            if (currentIndex > 0) {
                focusableArray[currentIndex - 1].focus();
            }
        }

        // Handle keyboard navigation
        form.addEventListener('keydown', function(event) {
            const activeElement = document.activeElement;

            // Enter key behavior
            if (event.key === 'Enter' && !event.shiftKey) {
                // Don't submit if pressing Enter in a non-submit element
                if (activeElement.tagName === 'TEXTAREA' ||
                    (activeElement.tagName === 'INPUT' && activeElement.type !== 'submit')) {
                    event.preventDefault();
                    focusNext();
                    return false;
                }
            }

            // Shift+Enter or Esc for reverse navigation
            if ((event.key === 'Enter' && event.shiftKey) || event.key === 'Escape') {
                event.preventDefault();
                focusPrevious();
                return false;
            }
        });

        // Your existing autocomplete and other JavaScript code can go here
        // ...
    });
</script>

@endsection
