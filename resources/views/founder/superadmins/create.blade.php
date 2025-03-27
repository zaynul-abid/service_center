@extends('backend.layouts.app')

@section('title','Superadmin-Creation')

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Create Superadmin</h4>
        </div>

        <div class="card-body">

            @if ($errors->has('error'))  <!-- For your transaction errors -->
            <div class="alert alert-danger">
                {{ $errors->first('error') }}
            </div>
            @endif

            <form action="{{ route('superadmins.store') }}" method="POST" id="superadminForm">
                @csrf
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" data-next="email">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}" data-next="password">
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                           data-next="password_confirmation">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           data-next="company_id">
                </div>

                <div class="mb-3">
                    <label>Select Company</label>
                    <select name="company_id" class="form-control @error('company_id') is-invalid @enderror"
                            data-submit-on-enter>
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

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('superadminForm').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const current = e.target;

                if (current.hasAttribute('data-submit-on-enter')) {
                    this.submit();
                } else if (current.hasAttribute('data-next')) {
                    const next = this.querySelector('[name="' + current.getAttribute('data-next') + '"]');
                    next && next.focus();
                }
            }
        });
    </script>

@endsection
