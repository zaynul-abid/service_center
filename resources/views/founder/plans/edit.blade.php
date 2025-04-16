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
                        <h5 class="mb-0">Edit Plan</h5>
                    </div>

                    <div class="card-body">
                        <form id="editPlanForm" action="{{ route('plans.update', $plan->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Plan Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                       value="{{ old('name', $plan->name) }}" placeholder="Enter plan name" required>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="amount" class="form-label">Amount</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" id="amount" name="amount" class="form-control"
                                               value="{{ old('amount', $plan->amount) }}" placeholder="0.00" min="0" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="days" class="form-label">Duration (Days)</label>
                                    <input type="number" id="days" name="days" class="form-control"
                                           value="{{ old('days', $plan->days) }}" placeholder="30" min="1" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" name="status" class="form-select" required>
                                    <option value="1" {{ old('status', $plan->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $plan->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('plans.index') }}" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-outline-dark">Update Plan</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.document.getElementById('editPlanForm'); // or use a specific ID if needed

            form.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && event.target.nodeName !== 'TEXTAREA') {
                    event.preventDefault();
                }
            });
        });
    </script>

@endpush
