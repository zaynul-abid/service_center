<!DOCTYPE html>
<html>
<head>
    <title>Employee Service Report</title>
    <style>
        /* Minimal Base Styles */
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
            font-size: 10pt;
            line-height: 1.35;
            color: #333;
            margin: 15mm 10mm;
            padding: 0;
        }

        /* A4 Page Constraints */
        @page {
            size: A4;
            margin: 0;
        }

        /* Clean Typography */
        .report-title {
            font-size: 18pt;
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 5mm 0;
            padding-bottom: 2mm;
            border-bottom: 1px solid #eee;
        }

        .report-meta {
            font-size: 9pt;
            color: #7f8c8d;
            margin-bottom: 5mm;
            display: flex;
            justify-content: space-between;
        }

        /* Minimal Table Design */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5mm 0;
        }

        th {
            text-align: left;
            padding: 3mm 2mm;
            font-weight: 600;
            font-size: 9pt;
            color: #7f8c8d;
            border-bottom: 2px solid #eee;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 3mm 2mm;
            border-bottom: 1px solid #f5f5f5;
            vertical-align: top;
        }

        /* Compact Status Indicators */
        .service-count {
            display: inline-block;
            min-width: 20px;
            text-align: center;
            padding: 1mm 2mm;
            border-radius: 10px;
            font-size: 9pt;
            font-weight: 600;
            background-color: #f5f5f5;
            color: #555;
        }

        .has-assignments {
            background-color: #e8f5e9;
            color: #2e7d32;
        }

        /* Compact Service Items */
        .service-item {
            margin-bottom: 2mm;
            font-size: 9pt;
        }

        .service-id {
            font-weight: 600;
            color: #1976d2;
        }

        .service-date {
            color: #9e9e9e;
            font-size: 8pt;
            margin-left: 2mm;
        }

        .service-remarks {
            color: #616161;
            font-size: 8pt;
            margin-top: 1mm;
            padding-left: 2mm;
            border-left: 2px solid #e0e0e0;
        }

        /* Footer */
        .report-footer {
            font-size: 8pt;
            color: #bdbdbd;
            text-align: center;
            margin-top: 10mm;
            padding-top: 2mm;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>

<!-- Report Header -->
<h1 class="report-title">Employee Service Report</h1>
<div class="report-meta">
    <span>Generated: {{ now()->format('M d, Y H:i') }}</span>
    <span>Total Employees: {{ $employees->count() }}</span>
    <span>Active Assignments: {{ $employees->sum('assigned_services_count') }}</span>
</div>

<!-- Employee Table -->
<table>
    <thead>
    <tr>
        <th width="5%">#</th>
        <th width="20%">Employee</th>
        <th width="15%">Position</th>
        <th width="15%">Contact</th>
        <th width="15%">Department</th>
        <th width="10%">Tasks</th>
        <th width="20%">Recent Services</th>
    </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <div style="font-weight: 600;">{{ $employee->name }}</div>
                <div style="font-size: 8pt; color: #757575;">{{ $employee->email }}</div>
            </td>
            <td>{{ $employee->position }}</td>
            <td>{{ $employee->phone }}</td>
            <td>
                @if($employee->department)
                    {{ $employee->department->name }}
                @else
                    <span style="color: #bdbdbd;">—</span>
                @endif
            </td>
            <td>
                <span class="service-count {{ $employee->assigned_services_count > 0 ? 'has-assignments' : '' }}">
                    {{ $employee->assigned_services_count }}
                </span>
            </td>
            <td>
                @if($employee->services->isNotEmpty())
                    @foreach($employee->services->take(3) as $service)
                        <div class="service-item">
                            <div>
                                <span class="service-id">#{{ $service->booking_id }}</span>
                                <span class="service-date">{{ $service->created_at->format('M d') }}</span>
                            </div>
                            @if($service->employee_remarks)
                                <div class="service-remarks">
                                    {{ Str::limit($service->employee_remarks, 40) }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @if($employee->services->count() > 3)
                        <div style="font-size: 8pt; color: #9e9e9e;">
                            +{{ $employee->services->count() - 3 }} more
                        </div>
                    @endif
                @else
                    <div style="font-size: 9pt; color: #bdbdbd; font-style: italic;">
                        No assignments
                    </div>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Footer -->
<div class="report-footer">
   {{ now()->format('M d, Y') }}
</div>

</body>
</html>
