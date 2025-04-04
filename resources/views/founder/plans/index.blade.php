@extends('backend.layouts.app')

@section('title','Plan-Index')

@section('navbar')
    @include('founder.partials.navbar')
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>Plans Management</h5>
            <a href="{{ route('plans.create') }}" class="btn btn-primary">Create Plan</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Duration (Days)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($plans as $plan)
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>₹{{ number_format($plan->amount, 2) }}</td>
                        <td>{{ $plan->days }}</td>
                        <td>
                            {{ $plan->status == 1 ? 'Active' : 'Inactive' }}
                        </td>

                        <td>
                            <a href="{{ route('plans.edit', $plan->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('plans.destroy', $plan->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{ $plans->links() }}
        </div>
    </div>
@endsection

