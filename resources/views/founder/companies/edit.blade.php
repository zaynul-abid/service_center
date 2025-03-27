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

    <div class="card-body">
        <form action="{{ route('companies.update', $company->id) }} " method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-control" value="{{ $company->company_name }}">
                @error('company_name') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" class="form-control" value="{{ $company->contact_number }}">
                @error('contact_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control">{{ $company->address }}</textarea>
                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Registration Number</label>
                <input type="text" name="registration_number" class="form-control" value="{{ $company->registration_number }}">
                @error('registration_number') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Plan</label>
                <input type="text" name="plan" class="form-control" value="{{ $company->plan }}">
                @error('plan') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- ✅ Subscription Start Date -->
            <div class="mb-3">
                <label class="form-label">Subscription Start Date</label>
                <input type="date" name="subscription_start_date" class="form-control" value="{{ $company->subscription_start_date }}">
                @error('subscription_start_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <!-- ✅ Subscription End Date -->
            <div class="mb-3">
                <label class="form-label">Subscription End Date</label>
                <input type="date" name="subscription_end_date" class="form-control" value="{{ $company->subscription_end_date }}">
                @error('subscription_end_date') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>

@endsection
