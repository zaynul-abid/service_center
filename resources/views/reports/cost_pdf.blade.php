<!DOCTYPE html>
<html>
<head>
    <title>Service Cost Report</title>
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
        .total-row {
            font-weight: bold;
            background-color: #e8f4fc;
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

        /* Header Info */
        .report-info {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        .report-info div {
            font-size: 9pt;
        }
        .filter-info {
            background-color: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 9pt;
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
<h1>Service Cost Report</h1>

<div class="report-info">
    <div>Generated on: {{ now()->format('M d, Y h:i A') }}</div>
    <div>Page 1 of <span id="pageTotal">1</span></div>
</div>

<!-- Filter Information -->
@if(request()->has('start_date') || request()->has('status'))
    <div class="filter-info">
        <strong>Filters Applied:</strong>
        @if(request()->has('start_date') && request()->has('end_date'))
            Date Range: {{ request('start_date') }} to {{ request('end_date') }}
        @endif
        @if(request()->has('status'))
            @if(request()->has('start_date')) | @endif
            Status: {{ ucfirst(request('status')) }}
        @endif
    </div>
@endif

<!-- Summary Table -->
<table>
    <thead>
    <tr>
        <th colspan="2">Cost Summary</th>
    </tr>
    </thead>
    <tbody>
    <tr class="total-row">
        <td>Total Service Cost</td>
        <td class="text-right">{{ number_format($totalServiceCost, 2) }}</td>
    </tr>
    <tr>
        <td>Pending Services</td>
        <td class="text-right">{{ number_format($pendingServiceCost, 2) }}</td>
    </tr>
    <tr>
        <td>Completed Services</td>
        <td class="text-right">{{ number_format($completedServiceCost, 2) }}</td>
    </tr>
    </tbody>
</table>

<!-- Detailed Services Table -->
<h2>Service Details</h2>
<table>
    <thead>
    <tr>
        <th width="4%">#</th>
        <th width="10%">Booking No.</th>
        <th width="10%">Date</th>
        <th width="15%">Customer</th>
        <th width="10%">Vehicle</th>
        <th width="35%">Service Details</th>
        <th width="8%" class="text-right">Cost </th>
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
            <td>{{ $service->vehicle_number }}</td>
            <td>{{ $service->service_details }}</td>
            <td class="text-right">{{ number_format($service->cost, 2) }}</td>
            <td class="status-{{ str_replace(' ', '_', $service->status) }}">
                {{ ucfirst($service->status) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Footer -->
<div class="footer">
    Generated on {{ now()->format('M d, Y') }}
</div>

<script>
    // Simple page counter (for PDF)
    document.addEventListener('DOMContentLoaded', function() {
        const pageCount = Math.ceil(document.querySelector('table').offsetHeight / 900);
        document.getElementById('pageTotal').textContent = pageCount > 0 ? pageCount : 1;
    });
</script>

</body>
</html>
