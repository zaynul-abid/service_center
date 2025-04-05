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
                <h5 class="card-title fw-semibold">Edit Employee</h5>
                <form action="{{ route('employees.update', $employee->id) }}" method="POST" id="employeeForm">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               id="name" value="{{ old('name', $employee->name) }}" required
                               data-next="email">
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" value="{{ old('email', $employee->email) }}" required
                               data-next="phone" data-prev="name">
                        @error('email')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" value="{{ old('phone', $employee->phone) }}"
                               data-next="department_id" data-prev="email">
                        @error('phone')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Department --}}
                    <div class="mb-3">
                        <label for="department_id" class="form-label">Department</label>
                        <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror"
                                data-next="position" data-prev="phone">
                            <option value="">Select Department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
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
                               id="position" value="{{ old('position', $employee->position) }}"
                               data-next="password" data-prev="department_id">
                        @error('position')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Password (Optional) --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password (Leave blank if unchanged)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" data-next="password_confirmation" data-prev="position">
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               id="password_confirmation" data-next="updateBtn" data-prev="password">
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-success" id="updateBtn">Update Employee</button>
                    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('employeeForm');

            if (form) {
                // Handle Enter and Escape key navigation
                form.addEventListener('keydown', function(e) {
                    const activeElement = document.activeElement;

                    // Enter key - move to next field or submit if on submit button
                    if (e.key === 'Enter') {
                        if (activeElement.type === 'submit') {
                            // Allow normal form submission when Enter is pressed on submit button
                            return true;
                        }

                        e.preventDefault();
                        const nextFieldId = activeElement.getAttribute('data-next');
                        if (nextFieldId) {
                            const nextField = document.getElementById(nextFieldId);
                            if (nextField) {
                                nextField.focus();
                            }
                        }
                    }

                    // Escape key - move to previous field
                    if (e.key === 'Escape') {
                        e.preventDefault();
                        const prevFieldId = activeElement.getAttribute('data-prev');
                        if (prevFieldId) {
                            const prevField = document.getElementById(prevFieldId);
                            if (prevField) {
                                prevField.focus();
                            }
                        }
                    }
                });


            }
        });
    </script>

@endsection
