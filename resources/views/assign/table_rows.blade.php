@forelse($services as $service)
    <tr class="py-1">
        <td class="ps-2 pe-1 py-1">
            <div class="form-check">
                <input class="form-check-input m-0" type="checkbox" name="service_ids[]" value="{{ $service->id }}">
            </div>
        </td>
        <td class="px-2 py-1">{{ $service->booking_id }}</td>
        <td class="px-2 py-1">
            <div class="d-flex flex-column">
                <small class="text-muted">{{ $service->booking_date }}</small>
                <small>{{ $service->booking_time }}</small>
            </div>
        </td>
        <td class="px-2 py-1">{{ $service->customer_name }}</td>
        <td class="px-2 py-1">{{ $service->contact_number_1 }}</td>
        <td class="px-2 py-1">
            <span class="badge rounded-pill bg-{{
                $service->status == 'Completed' ? 'success' :
                ($service->status == 'pending' ? 'warning text-dark' : 'primary')
            }}">
                {{ $service->status }}
            </span>
        </td>
        <td class="px-2 py-1">{{ $service->vehicle_number }}</td>
        <td class="px-2 py-1">{{ $service->employee->name ?? 'Unassigned' }}</td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-3">No services found</td>
    </tr>
@endforelse
