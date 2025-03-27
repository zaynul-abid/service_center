<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enquiry Report</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Employee Report</h2>
    <table>
            <thead>
                <tr>
                    <th>Sl.No</th> {{-- Order Number --}}
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Phone Number</th>
                    <th>Email</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $index => $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td> {{-- Numeric order starting from 1 --}}
                        <td>{{ $employee->name }}</td>
                        <td>{{ $employee->position }}</td>
                        <td>{{ $employee->phone }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->department ? $employee->department->name : 'N/A' }}</td> {{-- Department Name --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>
    </html>