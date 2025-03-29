@extends('backend.layouts.app')

@section('title','Company Creation')
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
                <h5 class="mb-0">Create Company</h5>
            </div>
            <div class="card-body">
                @if ($errors->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ $errors->first('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('companies.store') }}" method="POST" id="companyForm" class="needs-validation" novalidate>
                    @csrf

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="company_name"
                                   name="company_name"
                                   class="form-control @error('company_name') is-invalid @enderror"
                                   value="{{ old('company_name') }}"
                                   data-next="contact_number"
                                   data-previous=""
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
                                   value="{{ old('contact_number') }}"
                                   data-next="address"
                                   data-previous="company_name"
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
                                  data-next="registration_number"
                                  data-previous="contact_number"
                                  required>{{ old('address') }}</textarea>
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
                                   value="{{ old('registration_number') }}"
                                   data-next="plan_id"
                                   data-previous="address"
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
                                    data-next="subscription_start_date"
                                    data-previous="registration_number"
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

                    <div id="planDetails" class="border rounded p-3 mb-3" style="display: none;">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="plan_amount" class="form-label">Plan Amount</label>
                                <input type="text" id="plan_amount" name="plan_amount" class="form-control" readonly>
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
                                   data-previous="plan_id"
                                   value="{{ old('subscription_start_date') }}"
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
                        <button type="reset" class="btn btn-light me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            Create Company
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="{{asset('founder_assets/create_company/js/script.js')}}"></script>
@endpush
