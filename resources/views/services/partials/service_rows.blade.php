@foreach ($services as $service)
    <tr>
        <td class="ps-3 fw-medium text-primary border-end">{{ $service->booking_id }}</td>
        <td class="border-end">
            <div class="text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($service->booking_date)->format('d M Y') }}
            </div>
        </td>
        <td class="fw-medium border-end">{{ $service->customer_name }}</td>
        <td class="border-end">
            <div class="d-flex flex-column">
                <span class="fw-medium">{{ $service->vehicle_number }}</span>
                <small class="text-gray-500">{{ $service->vehicle_model }}</small>
            </div>
        </td>
        <td class="border-end">{{ $service->contact_number_1 }}</td>
        <td class="border-end">
            <div class="text-sm text-gray-600">
                {{ \Carbon\Carbon::parse($service->expected_delivery_date)->format('d M Y') }}
            </div>
        </td>
        <td class="border-end">
        <span class="badge rounded-pill py-1 px-3
            @if($service->status === 'completed') bg-success-light text-success
            @elseif($service->status === 'in_progress') bg-warning-light text-warning
            @elseif($service->status === 'pending') bg-info-light text-info
            @else bg-secondary-light text-secondary @endif">
            {{ ucfirst(str_replace('_', ' ', $service->status)) }}
        </span>
        </td>
        <td class="border-end">
        <span class="badge rounded-pill py-1 px-3
            @if($service->status === 'completed') bg-success-light text-success
            @elseif($service->status === 'in_progress') bg-warning-light text-warning
            @elseif($service->status === 'pending') bg-info-light text-info
            @else bg-secondary-light text-secondary @endif">
            {{ ucfirst(str_replace('_', ' ', $service->service_status)) }}
        </span>
        </td>
        <td class="fw-medium text-gray-900 border-end">₹{{ number_format($service->cost, 2) }}</td>
        <td class="border-end">
            @php
                // Decode JSON if string, or pass as-is if already an array
                $photos = is_string($service->photos) ? json_decode($service->photos, true) : $service->photos;
            @endphp

            @if (!empty($photos) && is_array($photos) && count($photos) > 0)
                <button class="btn btn-sm btn-outline-secondary view-images-btn"
                        data-service-id="{{ $service->id }}">
                    <i class="bi bi-images me-1"></i> View
                </button>
            @else
                <span class="text-gray-400 small">-</span>
            @endif
        </td>

        <td class="pe-3 text-end">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('services.edit', $service->id) }}"
                   class="btn btn-sm btn-icon btn-outline-primary rounded-circle"
                   title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('services.destroy', $service->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm btn-icon btn-outline-danger rounded-circle"
                            title="Delete"
                            onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@endforeach
