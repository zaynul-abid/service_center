<!DOCTYPE html>
<html>
<head>
    <title>Service Report</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 10mm;
        }

        /* Page Setup for A4 */
        @page {
            size: A4;
            margin: 15mm;
        }

        /* Typography */
        h1, h2, h3 {
            color: #2c3e50;
            margin: 5px 0;
        }
        h1 {
            font-size: 16pt;
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        h2 {
            font-size: 14pt;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        h3 {
            font-size: 12pt;
        }

        /* Header Info */
        .header {
            margin-bottom: 15px;
        }
        .title {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
        }
        .period {
            font-size: 9pt;
            color: #666;
            text-align: center;
            margin-bottom: 10px;
        }
        .filters {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9pt;
            text-align: center;
        }

        /* Summary Cards */
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            page-break-after: avoid;
        }
        .summary-card {
            width: 32%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
            background-color: #f8f9fa;
        }
        .summary-card h3 {
            font-size: 16pt;
            margin: 5px 0;
            color: #2c3e50;
        }
        .summary-card p {
            margin: 0;
            color: #666;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            page-break-inside: avoid;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #2c3e50;
            font-size: 9pt;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Special Classes */
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .status-pending {
            color: #e67e22;
            font-weight: bold;
        }
        .status-completed {
            color: #27ae60;
            font-weight: bold;
        }
        .status-in_progress {
            color: #3498db;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            font-size: 8pt;
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

<!-- Report Header -->
<div class="header">
    <div class="title">Service Report</div>
    <div class="period">Generated on: {{ now()->format('M d, Y h:i A') }}</div>

    @if(request('status') || request('start_date'))
        <div class="filters">
            <strong>Filters Applied:</strong>
            @if(request('status'))
                <span>Status: {{ ucfirst(request('status')) }}</span>
            @endif
            @if(request('start_date') && request('end_date'))
                @if(request('status')) | @endif
                <span>Date Range: {{ request('start_date') }} to {{ request('end_date') }}</span>
            @endif
        </div>
    @endif
</div>

<!-- Summary Cards -->
<div class="summary">
    <div class="summary-card">
        <p>Total Services</p>
        <h3>{{ number_format($totalServices) }}</h3>
    </div>
    <div class="summary-card">
        <p>Pending Services</p>
        <h3>{{ number_format($pendingServices) }}</h3>
    </div>
    <div class="summary-card">
        <p>Completed Services</p>
        <h3>{{ number_format($completedServices) }}</h3>
    </div>
</div>

<!-- Services Table -->
<table>
    <thead>
    <tr>
        <th width="4%">#</th>
        <th width="10%">Booking ID</th>
        <th width="10%">Date</th>
        <th width="15%">Customer</th>
        <th width="15%">Vehicle</th>
        <th width="30%">Service</th>
        <th width="8%" class="text-right">Amount</th>
        <th width="8%">Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($services as $service)
        <tr>
            <td class="text-center">{{ $loop->iteration }}</td>
            <td>{{ $service->booking_id }}</td>
            <td>{{ \Carbon\Carbon::parse($service->booking_date)->format('M d, Y') }}</td>
            <td>{{ $service->customer_name }}</td>
            <td>{{ $service->vehicle_number }}<br><small>{{ $service->vehicle_model }}</small></td>
            <td>{{ $service->service_details }}</td>
            <td class="text-right">{{ number_format($service->cost, 2) }}</td>
            <td class="status-{{ $service->status }}">
                {{ ucfirst(str_replace('_', ' ', $service->status)) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Footer -->
<div class="footer">
    Generated on {{ now()->format('M d, Y') }}
</div>

</body>
</html>
