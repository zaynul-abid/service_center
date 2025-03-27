@forelse($services as $service)
    @php
        $isOverdue = $service->days_difference < 0;
        $isDueToday = $service->days_difference == 0;
    @endphp
    
    <tr @if($isOverdue) class="table-danger"
        @elseif($isDueToday) class="table-warning" @endif>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $service->formatted_booking_date }}</td>
        <td>{{ $service->customer_name }}</td>
        <td>
            <div>{{ $service->vehicle_model }}</div>
            <small class="text-muted">{{ $service->vehicle_number }}</small>
        </td>
        <td>{{ $service->formatted_delivery_date }}</td>
        <td>
            <span class="badge 
                @if($service->service_status == 'completed') bg-success
                @elseif($service->service_status == 'in_progress') bg-primary
                @else bg-secondary @endif">
                {{ ucfirst(str_replace('_', ' ', $service->service_status)) }}
            </span>
        </td>
        <td>
            @if($isOverdue)
                <span class="badge bg-danger">Overdue by {{ abs($service->days_difference) }}d</span>
            @elseif($isDueToday)
                <span class="badge bg-warning">Due Today</span>
            @else
                <span class="badge bg-success">{{ $service->days_difference }}d left</span>
            @endif
        </td>
        <td>{{ $service->employee_name ?? 'Unassigned' }}</td>
        <td>
            <a href="{{ route('services.edit', $service->id) }}" 
               class="btn btn-sm btn-outline-primary"
               title="Edit">
                <i class="fas fa-edit"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="9" class="text-center text-muted py-4">
            No services found matching your criteria
        </td>
    </tr>
@endforelse