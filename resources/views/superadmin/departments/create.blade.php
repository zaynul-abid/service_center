@extends('backend.layouts.app')

@section('title', 'Create Department')

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

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="col-lg-8 mx-auto mt-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Create Department</h5>

                <form action="{{ route('departments.store') }}" method="POST" id="departmentForm">
                    @csrf

                    {{-- Department Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Department Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               id="name" value="{{ old('name') }}" required>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" id="description">{{ old('description') }}</textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if(auth()->user()->usertype === 'founder')
                        <div class="mb-3">
                            <label for="company_id">Select Company</label>
                            <select name="company_id" class="form-control" id="company_id">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-success">Save Department</button>
                    <a href="{{ route('departments.index') }}" class="btn btn-secondary">Back</a>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('departmentForm');

            if (form) {
                form.addEventListener('keydown', function(e) {
                    // Get all focusable elements in the form
                    const focusableElements = Array.from(
                        form.querySelectorAll('input, textarea, select, button, a.btn')
                    );

                    // Find current focused element
                    const currentIndex = focusableElements.indexOf(document.activeElement);

                    // Enter key - move forward (except for textarea)
                    if (e.key === 'Enter' && currentIndex > -1 && e.target.tagName !== 'TEXTAREA') {
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
                    if (e.key === 'Escape' && currentIndex > -1) {
                        e.preventDefault();

                        // If not first element, move to previous
                        if (currentIndex > 0) {
                            focusableElements[currentIndex - 1].focus();
                        }
                    }
                });
            }
        });
    </script>
@endsection
