@extends('backend.layouts.app')

@section('title', 'Edit Employee')

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
                        <h5 class="mb-0">Edit Employee</h5>
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

                        <form action="{{ route('employees.update', $employee->id) }}" method="POST" id="employeeForm" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $employee->name) }}" required
                                           data-next="email">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $employee->email) }}" required
                                           data-next="phone" data-previous="name">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" name="phone" id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $employee->phone) }}"
                                           data-next="department_id" data-previous="email">
                                    @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="department_id" class="form-label">Department *</label>
                                    <select name="department_id" id="department_id"
                                            class="form-control @error('department_id') is-invalid @enderror"
                                            data-next="position" data-previous="phone">
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="position" class="form-label">Position *</label>
                                <input type="text" name="position" id="position"
                                       class="form-control @error('position') is-invalid @enderror"
                                       value="{{ old('position', $employee->position) }}" required
                                       data-next="password" data-previous="department_id">
                                @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">New Password (Leave blank if unchanged)</label>
                                    <input type="password" name="password" id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           data-next="password_confirmation" data-previous="position">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Minimum 8 characters</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="form-control"
                                           data-previous="password">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end border-top pt-3">
                                <a href="{{ route('employees.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-success">Update Employee</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('employeeForm');

            // 1. Prevent Enter key form submission
            form.addEventListener('keydown', function(e) {
                // Check if Enter was pressed on input/select (excluding buttons/textarea)
                if (e.key === 'Enter' &&
                    (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') &&
                    e.target.type !== 'submit' &&
                    e.target.type !== 'button') {
                    e.preventDefault();
                    e.stopPropagation();
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

                    // Special handling for select elements
                    if (current.tagName === 'SELECT') {
                        // Only move focus when dropdown is closed (not during option selection)
                        if (!current.matches(':focus-within')) {
                            if (current.hasAttribute('data-next')) {
                                const nextField = document.getElementById(current.getAttribute('data-next'));
                                if (nextField) {
                                    nextField.focus();
                                }
                            }
                        }
                        return;
                    }

                    // Regular input field handling
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

            // 4. Additional select element handling
            document.querySelectorAll('select').forEach(select => {
                select.addEventListener('keydown', function(e) {
                    // Allow arrow keys and space to work normally in select
                    if (['ArrowUp', 'ArrowDown', 'Space'].includes(e.key)) {
                        return;
                    }

                    // Prevent Enter from submitting while selecting
                    if (e.key === 'Enter' && this.matches(':focus-within')) {
                        e.stopPropagation();
                    }
                });

                // Close select on blur
                select.addEventListener('blur', function() {
                    this.size = 0;
                });
            });
        });
    </script>

@endsection

