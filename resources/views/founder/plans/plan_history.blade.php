@extends('backend.layouts.app')

@section('title', 'Plan-History')

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')



    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Subscription History</h4>

        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">
            <table id="datatablesSimple" class="table">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Company</th>
                    <th>Contact</th>
                    <th>Plan</th>
                    <th>Plan Amount</th>
                    <th>Duration</th>
                    <th>Discount</th>
                    <th>Final Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Renewal</th>

                </tr>
                </thead>
                <tbody>
                @foreach ($subscriptions as $key => $sub)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $sub->company_name ?? 'N/A' }}</td>
                        <td>{{ $sub->contact_number ?? 'N/A' }}</td>
                        <td>  {{ $sub->plan_name ?? 'N/A' }}</td>
                        <td>₹{{ number_format($sub->plan_amount ?? 0, 2) }}</td>
                        <td>{{ $sub->plan_duration_days ?? 0 }} days</td>
                        <td>{{ $sub->discount ?? 0 }}</td>
                        <td>₹{{ number_format($sub->final_amount ?? 0, 2) }}</td>
                        <td>
                            {{ $sub->start_date ?? 'N/A' }}
                        </td>
                        <td>
                            {{ $sub->end_date ?? 'N/A' }}
                        </td>
                        <td>
                                    <span class="badge bg-{{ $sub->is_renewal ? 'warning' : 'light' }}">
                                        {{ $sub->is_renewal ? 'Yes' : 'No' }}
                                    </span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
