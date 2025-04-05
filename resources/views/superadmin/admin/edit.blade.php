@extends('backend.layouts.app')

@section('title', 'Edit Admin')

@section('navbar')
    @if(auth()->user()->usertype === 'founder')
        @include('founder.partials.navbar')
    @elseif(auth()->user()->usertype === 'superadmin')
        @include('superadmin.partials.navbar')
    @elseif(auth()->user()->usertype === 'admin')
        @include('admin.partials.navbar')
    @endif
@endsection

@section('content')

    {{-- Success & Error Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Edit Admin</h5>
                <form action="{{ route('superadmin-admins.update', $superadmin_admin->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $superadmin_admin->name) }}" required>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $superadmin_admin->email) }}" required>
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
                        <label for="password" class="form-label">New Password (Leave blank if unchanged)</label>
                        <input type="password" name="password" id="password"
                               class="form-control @error('password') is-invalid @enderror">
                        @error('password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="btn btn-success">Update Admin</button>
                    <a href="{{ route('superadmin-admins.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>

@endsection
