@extends('backend.layouts.app')

@section('title','Edit Company')
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
                <h5 class="mb-0">Edit Company</h5>
            </div>
            <div class="card-body">
                @if ($errors->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ $errors->first('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('companies.update', $company->id) }}" method="POST" id="companyForm" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="company_name"
                                   name="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name', $company->company_name) }}"
                                   required>
                            @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="contact_number"
                                   name="contact_number"
                                   class="form-control @error('contact_number') is-invalid @enderror"
                                   value="{{ old('contact_number', $company->contact_number) }}"
                                   required>
                            @error('contact_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <textarea id="address"
                                  name="address"
                                  class="form-control @error('address') is-invalid @enderror"
                                  rows="2"
                                  required>{{ old('address', $company->address) }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="registration_number" class="form-label">Registration Number <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="registration_number"
                                   name="registration_number"
                                   class="form-control @error('registration_number') is-invalid @enderror"
                                   value="{{ old('registration_number', $company->registration_number) }}"
                                   required>
                            @error('registration_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="plan_id" class="form-label">Subscription Plan <span class="text-danger">*</span></label>
                            <select id="plan_id"
                                    name="plan_id"
                                    class="form-select @error('plan_id') is-invalid @enderror"
                                    onchange="updatePlanDetails()"
                                    required>
                                <option value="" disabled>Select a Plan</option>
                                @foreach($plans as $plan)
                                    <option value="{{ $plan->id }}" data-amount="{{ $plan->amount }}" data-days="{{ $plan->days }}"
                                        {{ $company->plan_id == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }} ({{ $plan->days }} days) - ₹{{ $plan->amount }}
                                    </option>
                                @endforeach
                            </select>
                            @error('plan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div id="planDetails" class="border rounded p-3 mb-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="plan_amount" class="form-label">Plan Amount</label>
                                <input type="text" id="plan_amount" name="plan_amount" class="form-control" value="{{ $company->plan_amount }}" readonly>
                            </div>

                            <div class="col-md-4">
                                <label for="discount" class="form-label">Discount (₹)</label>
                                <input type="number"
                                       id="discount"
                                       name="discount"
                                       class="form-control"
                                       value="{{ old('discount', $company->discount) }}"
                                       min="0"
                                       oninput="calculateFinalPrice()">
                            </div>

                            <div class="col-md-4">
                                <label for="final_price" class="form-label">Final Price</label>
                                <input type="text"
                                       id="final_price"
                                       name="final_price"
                                       class="form-control"
                                       value="{{ $company->final_price }}"
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
                                   value="{{ old('subscription_start_date', $company->subscription_start_date) }}"
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
                                   value="{{ old('subscription_end_date', $company->subscription_end_date) }}"
                                   readonly>
                            @error('subscription_end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end border-top pt-3">
                        <a href="{{ route('companies.index') }}" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Company</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('founder_assets/create_company/js/script.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            updatePlanDetails();
        });

        function updatePlanDetails() {
            let planSelect = document.getElementById('plan_id');
            let selectedPlan = planSelect.options[planSelect.selectedIndex];

            if (selectedPlan && selectedPlan.dataset.amount) {
                document.getElementById('plan_amount').value = selectedPlan.dataset.amount;
                updateEndDate();
            }
        }

        function updateEndDate() {
            let startDate = document.getElementById('subscription_start_date').value;
            let planSelect = document.getElementById('plan_id');
            let days = planSelect.options[planSelect.selectedIndex].dataset.days;

            if (startDate && days) {
                let endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + parseInt(days));

                document.getElementById('subscription_end_date').value = endDate.toISOString().split('T')[0];
            }
        }

        function calculateFinalPrice() {
            let planAmount = parseFloat(document.getElementById('plan_amount').value) || 0;
            let discount = parseFloat(document.getElementById('discount').value) || 0;
            document.getElementById('final_price').value = (planAmount - discount).toFixed(2);
        }
    </script>
@endpush
