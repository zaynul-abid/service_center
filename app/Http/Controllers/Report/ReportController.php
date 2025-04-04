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
        return view('reports.service_report', compact('services'));
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

    public function showServiceCost(Request $request)
    {
        $query = Service::query();

        // Apply date range filter
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('booking_date', [$request->start_date, $request->end_date]);
        }

        // Fetch filtered services
        $services = $query->get();

        // Calculate costs correctly using query (not collection)
        $totalServiceCost = $query->sum('cost');
        $pendingServiceCost = $query->where('status', 'pending')->sum('cost');
        $completedServiceCost = $query->where('status', 'completed')->sum('cost');

        return view('reports.cost_report', compact(
            'services',
            'totalServiceCost',
            'pendingServiceCost',
            'completedServiceCost'
        ));
    }


    public function downloadServiceCostReport(Request $request)
    {
        $query = Service::query();

        // Apply date range filter if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('booking_date', [$request->start_date, $request->end_date]);
        }

        $services = $query->get();

        // Calculate costs based on filtered data
        $totalServiceCost = $services->sum('cost');
        $pendingServiceCost = $query->where('status', 'pending')->sum('cost');
        $completedServiceCost = $query->where('status', 'completed')->sum('cost');


        // Load the PDF view with filtered data
        $pdf = PDF::loadView('reports.cost_pdf', compact(
            'services',
            'totalServiceCost',
            'pendingServiceCost',
            'completedServiceCost'
        ));

        // Download the PDF
        return $pdf->download('service_cost_report.pdf');
    }

}
