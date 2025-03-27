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

<div class="col-lg-12 mx-auto">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title fw-semibold">Add New Admin</h5>
            <form action="{{ route('superadmin-admins.store') }}" method="POST" id="adminForm">
                @csrf

                {{-- Name --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           id="name" value="{{ old('name') }}" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           id="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @if(auth()->user()->usertype === 'founder')
                <div class="mb-3">
                    <label>Select Company</label>
                    <select name="company_id" class="form-control">
                        <option value="">Select Company</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" required>
                </div>

                {{-- Submit Button --}}
                <button type="submit" class="btn btn-primary">Create Admin</button>
                <a href="{{ route('superadmin-admins.index') }}" class="btn btn-secondary">Cancel</a>

            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('adminForm');

        if (form) {
            form.addEventListener('keydown', function(e) {
                // Get all focusable elements in the form
                const focusableElements = Array.from(form.querySelectorAll('input, select, textarea, button'));
                const currentIndex = focusableElements.indexOf(document.activeElement);

                // Enter key - move forward
                if (e.key === 'Enter') {
                    e.preventDefault();

                    // If not last element, move to next
                    if (currentIndex < focusableElements.length - 1) {
                        focusableElements[currentIndex + 1].focus();
                    }
                    // If last element, submit the form
                    else {
                        form.submit();
                    }
                }

                // Escape key - move backward
                if (e.key === 'Escape') {
                    e.preventDefault();

                    // If not first element, move to previous
                    if (currentIndex > 0) {
                        focusableElements[currentIndex - 1].focus();
                    }
                    // If first element, focus stays there
                }
            });
        }
    });
</script>


@endsection
