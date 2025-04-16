@foreach ($services as $service)
    <div class="card service-card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h5 class="card-title text-primary mb-0">{{ $service->booking_id }}</h5>
                <span class="badge rounded-pill py-1 px-3
                @if($service->status === 'completed') bg-success-light text-success
                @elseif($service->status === 'in_progress') bg-warning-light text-warning
                @elseif($service->status === 'pending') bg-info-light text-info
                @else bg-secondary-light text-secondary @endif">
                {{ ucfirst(str_replace('_', ' ', $service->status)) }}
            </span>
            </div>

            <div class="mb-2">
                <strong>Customer:</strong> {{ $service->customer_name }}
            </div>

            <div class="mb-2">
                <strong>Vehicle:</strong> {{ $service->vehicle_number }} ({{ $service->vehicle_model }})
            </div>

            <div class="mb-2">
                <strong>Contact:</strong> {{ $service->contact_number_1 }}
            </div>

            <div class="mb-2">
                <strong>Booking Date:</strong>
                {{ \Carbon\Carbon::parse($service->booking_date)->format('d M Y') }}
            </div>

            <div class="mb-2">
                <strong>Delivery Date:</strong>
                {{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('d M Y') }}
            </div>

            <div class="mb-2">
                <strong>Cost:</strong> ₹{{ number_format($service->cost, 2) }}
            </div>


            <div class="mb-2">
                @if (!empty($service->photos) && is_string($service->photos))
                    <button class="btn btn-sm btn-outline-secondary view-images-btn"
                            data-service-id="{{ $service->id }}">
                        <i class="bi bi-images me-1"></i> View Images
                    </button>
                @else
                    <span class="text-muted small">No images</span>
                @endif
            </div>

            <div class="d-flex justify-content-end gap-2 mt-2">
                <a href="{{ route('services.edit', $service->id) }}"
                   class="btn btn-sm btn-outline-primary rounded-pill px-3"
                   title="Edit">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
                <form action="{{ route('services.destroy', $service->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm btn-outline-danger rounded-pill px-3"
                            title="Delete"
                            onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endforeach
