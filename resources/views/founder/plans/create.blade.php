@extends('backend.layouts.app')

@section('title','Plan-Index')

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Create New Plan</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('plans.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Plan Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter plan name" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="amount" class="form-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="amount" name="amount" class="form-control" placeholder="0.00" min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="days" class="form-label">Duration (Days)</label>
                                    <input type="number" id="days" name="days" class="form-control" placeholder="" min="" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="" selected disabled>Select status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                <button type="submit" class="btn btn-outline-dark">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>


        document.addEventListener('DOMContentLoaded', function() {
            // Get the form element
            const form = document.getElementById('form');

            if (form) {
                // Prevent form submission on Enter key (except for textareas)
                form.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
                        e.preventDefault();
                    }
                });

                // Get the reset button
                const resetButton = form.querySelector('button[type="reset"]');

                if (resetButton) {
                    // Make reset button refresh the page
                    resetButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        window.location.reload();
                    });
                }
            }
        });
    </script>
@endpush
