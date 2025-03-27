@extends('backend.layouts.app')

@section('title', 'Create Employee')

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



    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Add Employee</h5>
                <form action="{{ route('employees.store') }}" method="POST" id="employeeForm">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               id="name" value="{{ old('name') }}" required
                               data-next="email" data-prev="">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" value="{{ old('email') }}" required
                               data-next="phone" data-prev="name">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" value="{{ old('phone') }}" required
                               data-next="@if(auth()->user()->usertype === 'founder') company_id @else department_id @endif" data-prev="email">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    @if(auth()->user()->usertype === 'founder')
                        <div class="mb-3">
                            <label>Select Company</label>
                            <select name="company_id" class="form-control" required
                                    id="company_id" data-next="department_id" data-prev="phone">
                                <option value="">Select Company</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    {{-- Department --}}
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required
                                data-next="position" data-prev="@if(auth()->user()->usertype === 'founder') company_id @else phone @endif">
                            <option value="">Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Position --}}
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <input type="text" name="position" class="form-control @error('position') is-invalid @enderror"
                               id="position" value="{{ old('position') }}" required
                               data-next="password" data-prev="department_id">
                        @error('position')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" required
                               data-next="password_confirmation" data-prev="position">
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation" required
                               data-next="submitBtn" data-prev="password">
                        @error('password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-primary" id="submitBtn">Save Employee</button>

                </form>
{{--                <div>--}}
{{--                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>--}}

{{--                </div>--}}

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('employeeForm');

            if (form) {
                form.addEventListener('keydown', function(e) {
                    // Get all focusable elements in the form (inputs, selects, buttons)
                    const focusableElements = Array.from(
                        form.querySelectorAll('input, select, button, a.btn')
                    );

                    // Find current focused element
                    const currentIndex = focusableElements.indexOf(document.activeElement);

                    // Enter key - move forward
                    if (e.key === 'Enter' && currentIndex > -1) {
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
