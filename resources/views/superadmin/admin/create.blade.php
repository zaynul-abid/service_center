@extends('backend.layouts.app')

@section('title', 'Add New Admin')

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
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Add New Admin</h5>
                    </div>
                    <div class="card-body">
                        {{-- Success Message --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Error Message --}}
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('superadmin-admins.store') }}" method="POST" id="adminForm" class="needs-validation" novalidate>
                            @csrf

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name') }}" required
                                           data-next="email">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}" required
                                           data-next="company_id" data-previous="name">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @if(auth()->user()->usertype === 'founder')
                                <div class="mb-3">
                                    <label for="company_id" class="form-label">Company *</label>
                                    <select name="company_id" id="company_id"
                                            class="form-control @error('company_id') is-invalid @enderror"
                                            data-previous="email" data-next="password">
                                        <option value="">Select Company</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password *</label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           required data-next="password_confirmation" data-previous="company_id">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control" required
                                           data-previous="password">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <a href="{{ route('superadmin-admins.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Admin</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('adminForm');

            // 1. Prevent Enter key form submission except for submit button
            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' &&
                    e.target.tagName === 'INPUT' &&
                    e.target.type !== 'submit' &&
                    e.target.type !== 'button') {
                    e.preventDefault();
                    return false;
                }
            });

            // 2. Enhanced keyboard navigation
            form.addEventListener('keydown', function(e) {
                const current = e.target;

                // Enter key - move to next field
                if (e.key === 'Enter') {
                    e.preventDefault();

                    // Don't proceed if current field is invalid
                    if (!current.checkValidity()) {
                        current.classList.add('is-invalid');
                        return;
                    }

                    if (current.hasAttribute('data-next')) {
                        const nextField = document.getElementById(current.getAttribute('data-next'));
                        if (nextField) {
                            nextField.focus();
                        }
                    }
                }

                // Escape key - move to previous field
                if (e.key === 'Escape') {
                    e.preventDefault();
                    if (current.hasAttribute('data-previous') && current.getAttribute('data-previous') !== '') {
                        const previousField = document.getElementById(current.getAttribute('data-previous'));
                        if (previousField) {
                            previousField.focus();
                        }
                    }
                }
            });

            // 3. Bootstrap validation
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
@endsection



