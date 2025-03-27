<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function showServiceReport()
    {
        $services = Service::with('employee')->get();
        return view('reports.service_report', compact('services',));
    }

    public function showEmployeeReport()
    {
        $employees = Employee::all();
        return view('reports.employee_report', compact('employees',));
    }

    public function downloadServiceReport()
    {   
        $services = Service::with('employee')->get();


        $pdf = Pdf::loadView('reports.service_pdf', compact('services'));

        return $pdf->download('service_reports.pdf');
    }

    public function downloadEmployeeReport()
    {

        $employees = Employee::all();

        $pdf = Pdf::loadView('reports.employee_pdf', compact('employees'));

        return $pdf->download('employee_report.pdf');
    }
}