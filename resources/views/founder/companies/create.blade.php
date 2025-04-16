@extends('backend.layouts.app')

@section('title', 'Create Company')

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8"> <!-- This creates 70% width on large screens -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create Company</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="companyForm" action="{{ route('companies.store') }}" method="POST" novalidate>
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="company_name" class="form-label">Company Name *</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                    @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="contact_number" class="form-label">Contact Number *</label>
                                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror"
                                           id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                                    @error('contact_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address *</label>
                                <textarea class="form-control @error('address') is-invalid @enderror"
                                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="registration_number" class="form-label">Registration Number *</label>
                                    <input type="text" class="form-control @error('registration_number') is-invalid @enderror"
                                           id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required>
                                    @error('registration_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="plan_id" class="form-label">Plan *</label>
                                    <select class="form-select @error('plan_id') is-invalid @enderror"
                                            id="plan_id" name="plan_id" required>
                                        <option value="" disabled selected>Select Plan</option>
                                        @foreach($plans as $plan)
                                            <option value="{{ $plan->id }}"
                                                    data-amount="{{ $plan->amount }}"
                                                    data-days="{{ $plan->days }}"
                                                {{ old('plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} ({{ $plan->days }} days)
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('plan_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="planDetails" class="border rounded p-3 mb-3" style="display: none;">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="plan_amount" class="form-label">Plan Amount *</label>
                                        <input type="number" class="form-control @error('plan_amount') is-invalid @enderror"
                                               id="plan_amount" name="plan_amount" value="{{ old('plan_amount') }}" required>
                                        @error('plan_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="discount" class="form-label">Discount</label>
                                        <input type="number" class="form-control @error('discount') is-invalid @enderror"
                                               id="discount" name="discount" value="{{ old('discount', 0) }}" min="0">
                                        @error('discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-4">
                                        <label for="final_price" class="form-label">Final Price *</label>
                                        <input type="number" class="form-control @error('final_price') is-invalid @enderror"
                                               id="final_price" name="final_price" value="{{ old('final_price') }}" required>
                                        @error('final_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="subscription_start_date" class="form-label">Start Date *</label>
                                    <input type="date" class="form-control @error('subscription_start_date') is-invalid @enderror"
                                           id="subscription_start_date" name="subscription_start_date"
                                           value="{{ old('subscription_start_date') }}" required>
                                    @error('subscription_start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="subscription_end_date" class="form-label">End Date *</label>
                                    <input type="date" class="form-control @error('subscription_end_date') is-invalid @enderror"
                                           id="subscription_end_date" name="subscription_end_date"
                                           value="{{ old('subscription_end_date') }}" required>
                                    @error('subscription_end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <button type="button" id="resetButton" class="btn btn-secondary me-2">Reset</button>
                                <button type="submit" class="btn btn-primary">Create Company</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form elements
            const form = document.getElementById('companyForm');
            const planSelect = document.getElementById('plan_id');
            const planDetails = document.getElementById('planDetails');
            const planAmountInput = document.getElementById('plan_amount');
            const discountInput = document.getElementById('discount');
            const finalPriceInput = document.getElementById('final_price');
            const startDateInput = document.getElementById('subscription_start_date');
            const endDateInput = document.getElementById('subscription_end_date');
            const resetButton = document.getElementById('resetButton');

            // Initialize form if returning with errors
            if (planSelect.value) {
                updatePlanDetails();
            }

            // Form validation
            if (form) {
                form.addEventListener('submit', function(event) {
                    // First validate the plan selection
                    if (!planSelect.value) {
                        event.preventDefault();
                        event.stopPropagation();
                        planSelect.classList.add('is-invalid');
                        return;
                    }

                    // Then proceed with normal validation
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                }, false);

                // Keyboard navigation - improved version
                form.addEventListener('keydown', function(e) {
                    const activeElement = document.activeElement;
                    if (!activeElement || !form.contains(activeElement)) return;

                    // Get all focusable elements in the form (excluding submit button)
                    const focusableElements = form.querySelectorAll(
                        'input:not([type="hidden"]):not([readonly]):not([type="submit"]), select:not([disabled]), textarea:not([readonly])'
                    );
                    const focusable = Array.from(focusableElements);
                    const currentIndex = focusable.indexOf(activeElement);

                    // Handle Enter key navigation (move forward)
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();

                        // If we're already on the last field, do nothing
                        if (currentIndex === focusable.length - 1) {
                            return;
                        }

                        // If on a select element with data-next attribute
                        if (activeElement.tagName === 'SELECT' && activeElement.hasAttribute('data-next')) {
                            const nextField = form.querySelector(`[name="${activeElement.getAttribute('data-next')}"]`);
                            if (nextField) nextField.focus();
                        }
                        // If on an element with data-next attribute
                        else if (activeElement.hasAttribute('data-next')) {
                            const nextField = form.querySelector(`[name="${activeElement.getAttribute('data-next')}"]`);
                            if (nextField) nextField.focus();
                        }
                        // Default behavior - move to next focusable element
                        else if (currentIndex > -1 && currentIndex < focusable.length - 1) {
                            focusable[currentIndex + 1].focus();
                        }
                    }

                    // Handle backward navigation with Escape (move backward)
                    if (e.key === 'Escape') {
                        e.preventDefault();

                        // If element has data-previous attribute
                        if (activeElement.hasAttribute('data-previous') && activeElement.getAttribute('data-previous') !== '') {
                            const prevField = form.querySelector(`[name="${activeElement.getAttribute('data-previous')}"]`);
                            if (prevField) prevField.focus();
                        }
                        // Default behavior - move to previous focusable element
                        else if (currentIndex > 0) {
                            focusable[currentIndex - 1].focus();
                        }
                    }
                });
            }

            // Event listeners
            planSelect.addEventListener('change', updatePlanDetails);
            discountInput.addEventListener('input', calculateFinalPrice);
            startDateInput.addEventListener('change', updateEndDate);

            // Reset button handler - refreshes the page
            resetButton.addEventListener('click', function() {
                window.location.href = window.location.href;
            });

            // Functions
            function updatePlanDetails() {
                const selectedPlan = planSelect.options[planSelect.selectedIndex];
                const planAmount = selectedPlan?.getAttribute('data-amount');
                const days = selectedPlan?.getAttribute('data-days');

                if (planAmount) {
                    planDetails.style.display = 'block';
                    planAmountInput.value = planAmount;
                    discountInput.value = 0;
                    calculateFinalPrice();
                    updateEndDate();

                    // Remove readonly for validation
                    planAmountInput.removeAttribute('readonly');
                    finalPriceInput.removeAttribute('readonly');
                } else {
                    planDetails.style.display = 'none';
                    planAmountInput.value = '';
                    finalPriceInput.value = '';
                    discountInput.value = '';

                    // Add readonly back when hidden
                    planAmountInput.setAttribute('readonly', 'readonly');
                    finalPriceInput.setAttribute('readonly', 'readonly');
                }
            }

            function calculateFinalPrice() {
                const planAmount = parseFloat(planAmountInput.value) || 0;
                const discount = parseFloat(discountInput.value) || 0;

                // Ensure discount doesn't exceed plan amount
                const validatedDiscount = Math.min(discount, planAmount);
                if (discount !== validatedDiscount) {
                    discountInput.value = validatedDiscount;
                }

                const finalPrice = planAmount - validatedDiscount;
                finalPriceInput.value = finalPrice.toFixed(2);
            }

            function updateEndDate() {
                const selectedPlan = planSelect.options[planSelect.selectedIndex];
                const days = selectedPlan?.getAttribute('data-days');

                if (startDateInput.value && days) {
                    const startDate = new Date(startDateInput.value);
                    startDate.setDate(startDate.getDate() + parseInt(days));
                    endDateInput.value = startDate.toISOString().split('T')[0];
                } else {
                    endDateInput.value = '';
                }
            }
        });
    </script>
@endpush
