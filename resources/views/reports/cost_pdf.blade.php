<!DOCTYPE html>
<html>
<head>
    <title>Service Cost Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<h2>Service Cost Report</h2>

@if(request()->has('start_date') && request()->has('end_date'))
    <p><strong>Filtered Date Range:</strong> {{ request('start_date') }} to {{ request('end_date') }}</p>
@endif

<table>
    <thead>
    <tr>
        <th>Service Type</th>
        <th>Total Cost (₹)</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><strong>Total Service Cost</strong></td>
        <td><strong>{{ number_format($totalServiceCost, 2) }}</strong></td>
    </tr>
    <tr>
        <td>Pending Service Cost</td>
        <td>{{ number_format($pendingServiceCost, 2) }}</td>
    </tr>
    <tr>
        <td>Completed Service Cost</td>
        <td>{{ number_format($completedServiceCost, 2) }}</td>
    </tr>
    </tbody>
</table>

<h3>Individual Service Costs</h3>
<table>
    <thead>
    <tr>
        <th>Sl.No</th>
        <th>Booking Number</th>
        <th>Date</th>
        <th>Customer Name</th>
        <th>Vehicle Number</th>
        <th>Service Details</th>
        <th>Cost (₹)</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($services as $index => $service)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $service->booking_id }}</td>
            <td>{{ $service->booking_date }}</td>
            <td>{{ $service->customer_name }}</td>
            <td>{{ $service->vehicle_number }}</td>
            <td>{{ $service->service_details }}</td>
            <td>{{ number_format($service->cost, 2) }}</td>
            <td>{{ ucfirst($service->status) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
