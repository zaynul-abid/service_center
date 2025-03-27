<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Report</title>
    <style>
        @page {
            size: A4;
            margin: 7mm;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
            font-size: 10px;
            padding: 6px;
            font-weight: bold;
        }
        tr {
            page-break-inside: avoid;
        }
        h2 {
            text-align: center;
            margin: 8px 0;
            font-size: 16px;
        }
        /* Column width adjustments */
        td:nth-child(1) { width: 3%; }   /* Sl.No */
        td:nth-child(2) { width: 8%; }   /* Booking Number */
        td:nth-child(3) { width: 6%; }   /* Date */
        td:nth-child(4) { width: 12%; }  /* Customer Name */
        td:nth-child(5) { width: 9%; }   /* Vehicle Number */
        td:nth-child(6) { width: 10%; }  /* Vehicle Model */
        td:nth-child(7) { width: 8%; }   /* Contact Number */
        td:nth-child(8) { width: 16%; }  /* Service Details */
        td:nth-child(9) { width: 7%; }   /* Cost */
        td:nth-child(10) { width: 10%; }/* Assigned Employee */
        td:nth-child(11) { width: 7%; }  /* Status */
        td:nth-child(12) { width: 6%; }  /* Delivery Date */
    </style>
</head>
<body>
    <h2>Service Report</h2>
    <table>
        <thead>
            <tr>
                <th>Sl.No</th>
                <th>Booking #</th>
                <th>Date</th>
                <th>Customer</th>
                <th>Vehicle #</th>
                <th>Model</th>
                <th>Contact</th>
                <th>Service Details</th>
                <th>Cost</th>
                <th>Employee</th>
                <th>Status</th>
                <th>Delivery</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $service->booking_id }}</td>
                <td>{{ $service->booking_date }}</td>
                <td>{{ $service->customer_name }}</td>
                <td>{{ $service->vehicle_number }}</td>
                <td>{{ $service->vehicle_model }}</td>
                <td>{{ $service->contact_number_1 }}</td>
                <td>{{ $service->service_details }}</td>
                <td>{{ $service->cost }}</td>
                <td>{{ $service->employee ? $service->employee->name : '-' }}</td>
                <td>{{ $service->service_status }}</td>
                <td>{{ $service->expected_delivery_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>