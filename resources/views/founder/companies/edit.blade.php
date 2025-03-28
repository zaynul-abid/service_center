@extends('backend.layouts.app')

@section('title','Company-edit')

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')

    <div class="card">
        <div class="card-header">
            <h4>Edit Company</h4>
        </div>

        @if ($errors->has('error'))  <!-- For your transaction errors -->
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
        @endif

        <div class="card-body">
            <form action="{{ route('companies.update', $company->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                           value="{{ old('company_name', $company->company_name) }}" data-next="contact_number">
                    @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control @error('contact_number') is-invalid @enderror"
                           value="{{ old('contact_number', $company->contact_number) }}" data-next="address">
                    @error('contact_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                              data-next="registration_number">{{ old('address', $company->address) }}</textarea>
                    @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Registration Number</label>
                    <input type="text" name="registration_number" class="form-control @error('registration_number') is-invalid @enderror"
                           value="{{ old('registration_number', $company->registration_number) }}" data-next="plan">
                    @error('registration_number') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Plan</label>
                    <input type="text" name="plan" class="form-control @error('plan') is-invalid @enderror"
                           value="{{ old('plan', $company->plan) }}" data-next="subscription_start_date">
                    @error('plan') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Subscription Start Date</label>
                    <input type="date" name="subscription_start_date" class="form-control @error('subscription_start_date') is-invalid @enderror"
                           value="{{ old('subscription_start_date', $company->subscription_start_date) }}" data-next="subscription_end_date">
                    @error('subscription_start_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Subscription End Date</label>
                    <input type="date" name="subscription_end_date" class="form-control @error('subscription_end_date') is-invalid @enderror"
                           value="{{ old('subscription_end_date', $company->subscription_end_date) }}" data-submit-on-enter="true">
                    @error('subscription_end_date') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Update</button>
                </div>
            </form>
        </div>
    </div>


@endsection
