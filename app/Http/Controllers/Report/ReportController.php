<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function showServiceReport()
    {
        $services = Service::with('employee')->paginate(10);
        return view('reports.service_report', compact('services'));
    }

    public function showEmployeeReport()
    {
        $employees = Employee::with(['services' => function($query) {
            $query->select('id', 'booking_id', 'employee_id', 'employee_remarks', 'created_at')
                ->whereNotNull('created_at')
                ->orderBy('created_at', 'desc');
        }, 'department'])
            ->orderBy('name')
            ->paginate(10);

        return view('reports.employee_report', compact('employees'));
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
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('booking_date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Get the base query for cost calculations (before pagination)
        $costQuery = clone $query;

        // Calculate costs
        $totalServiceCost = $costQuery->sum('cost');
        $pendingServiceCost = (clone $costQuery)->where('status', 'pending')->sum('cost');
        $completedServiceCost = (clone $costQuery)->where('status', 'completed')->sum('cost');

        // Paginate the results with relationships
        $services = $query->orderBy('booking_date', 'desc')
            ->paginate($request->per_page ?? 15)
            ->appends($request->query());

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
        $totalServiceCost = $query->sum('cost');
        $pendingServiceCost = (clone $query)->where('status', 'pending')->sum('cost');
        $completedServiceCost = (clone $query)->where('status', 'completed')->sum('cost');


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
