@extends('backend.layouts.app')

@section('title','Company Renewal')
@push('styles')
    <link rel="stylesheet" href="{{ asset('founder_assets/create_company/css/style.css') }}">
@endpush

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Renew Company: {{ $company->company_name }}</h5>
            </div>
            <div class="card-body">
                @if ($errors->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ $errors->first('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('companies.process-renewal', $company->id) }}" method="POST" id="renewalForm" class="needs-validation" novalidate>
                    @csrf

                    <!-- Company Details (readonly) -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" value="{{ $company->company_name }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control" value="{{ $company->contact_number }}" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="2" readonly>{{ $company->address }}</textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Registration Number</label>
                            <input type="text" class="form-control" value="{{ $company->registration_number }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="plan_id" class="form-label">Subscription Plan <span class="text-danger">*</span></label>
                            <select id="plan_id"
                                    name="plan_id"
                                    class="form-select @error('plan_id') is-invalid @enderror"
                                    data-next="discount"
                                    data-previous=""
                                    onchange="updatePlanDetails()"
                                    required>
                                <option value="" selected disabled>Select a Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-amount="{{ $plan->amount }}" data-days="{{ $plan->days }}">
                                        {{ $plan->name }} ({{ $plan->days }} days) - ₹{{ $plan->amount }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Plan Details Section -->
                    <div id="planDetails" class="border rounded p-3 mb-3" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="plan_amount" class="form-label">Plan Amount</label>
                                <input type="text"
                                       id="plan_amount"
                                       name="plan_amount"
                                       class="form-control"
                                       readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="form-label">Discount (₹)</label>
                                <input type="number"
                                       id="discount"
                                       name="discount"
                                       class="form-control"
                                       data-next="subscription_start_date"
                                       data-previous="plan_id"
                                       value="0"
                                       min="0"
                                       oninput="calculateFinalPrice()">
                            </div>

                            <div class="col-md-4">
                                <label for="final_price" class="form-label">Final Price</label>
                                <input type="text"
                                       id="final_price"
                                       name="final_price"
                                       class="form-control"
                                       readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="subscription_start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                            <input type="date"
                                   id="subscription_start_date"
                                   name="subscription_start_date"
                                   class="form-control @error('subscription_start_date') is-invalid @enderror"
                                   data-previous="discount"
                                   onchange="updateEndDate()"
                                   required>
                            @error('subscription_start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="subscription_end_date" class="form-label">End Date</label>
                            <input type="date"
                                   id="subscription_end_date"
                                   name="subscription_end_date"
                                   class="form-control @error('subscription_end_date') is-invalid @enderror"
                                   readonly>
                            @error('subscription_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-3">
                        <a href="{{ route('companies.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            Complete Renewal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Set today's date as default start date
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('subscription_start_date').value = today;

            // Initialize enter key navigation
            initEnterKeyNavigation();
        });

        function initEnterKeyNavigation() {
            document.querySelectorAll('input, select').forEach(element => {
                element.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const nextField = this.getAttribute('data-next');
                        if (nextField) {
                            document.getElementById(nextField).focus();
                        }
                    }
                });
            });
        }

        function updateEndDate() {
            const planSelect = document.getElementById('plan_id');
            const startDateInput = document.getElementById('subscription_start_date');
            const endDateInput = document.getElementById('subscription_end_date');

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

        function updatePlanDetails() {
            const planSelect = document.getElementById('plan_id');
            const planDetails = document.getElementById('planDetails');
            const planAmountInput = document.getElementById('plan_amount');
            const finalPriceInput = document.getElementById('final_price');

            const selectedPlan = planSelect.options[planSelect.selectedIndex];
            const planAmount = selectedPlan?.getAttribute('data-amount');

            if (planAmount) {
                planDetails.style.display = 'block';
                planAmountInput.value = planAmount;
                finalPriceInput.value = planAmount;
                updateEndDate();
            } else {
                planDetails.style.display = 'none';
            }
        }

        function calculateFinalPrice() {
            const planAmount = parseFloat(document.getElementById('plan_amount').value) || 0;
            const discount = parseFloat(document.getElementById('discount').value) || 0;
            const finalPriceInput = document.getElementById('final_price');

            // Ensure discount doesn't exceed plan amount
            const validatedDiscount = Math.min(discount, planAmount);
            if (discount !== validatedDiscount) {
                document.getElementById('discount').value = validatedDiscount;
            }

            const finalPrice = planAmount - validatedDiscount;
            finalPriceInput.value = finalPrice.toFixed(2);
        }
    </script>
@endpush
