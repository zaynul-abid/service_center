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
    public function showServiceReport(Request $request)
    {
        $query = Service::query();

        // Apply status filter if selected
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply date range filter if both dates are selected
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('booking_date', [
                $request->start_date,
                $request->end_date
            ]);
        }

        // Clone query before pagination for counts
        $baseQuery = clone $query;

        // Paginate the result
        $services = $query->paginate(10);

        // Count by status
        $totalServices      = $baseQuery->count();
        $pendingServices    = (clone $baseQuery)->where('status', 'pending')->count();
        $completedServices  = (clone $baseQuery)->where('status', 'Completed')->count();
        $cancelledServices  = (clone $baseQuery)->where('status', 'cancelled')->count();
        $inProgressServices = (clone $baseQuery)->where('status', 'in_progress')->count();

        return view('reports.service_report', compact(
            'services',
            'totalServices',
            'pendingServices',
            'completedServices',
            'cancelledServices',
            'inProgressServices'
        ));
    }




    public function downloadServiceReport(Request $request)
    {
        $query = Service::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('booking_date', [$request->start_date, $request->end_date]);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->get();

        $totalServices = $query->count();
        $pendingServices = (clone $query)->where('status', 'pending')->count();
        $completedServices = (clone $query)->where('status', 'completed')->count();

        $pdf = PDF::loadView('reports.service_pdf', compact(
            'services',
            'totalServices',
            'pendingServices',
            'completedServices'
        ));

        return $pdf->download('service_report_'.now()->format('Y-m-d').'.pdf');
    }

    public function showEmployeeReport()
    {
        $employees = Employee::with([
            'services' => function($query) {
                $query->select('id', 'booking_id', 'employee_id', 'employee_remarks', 'created_at')
                    ->whereNotNull('employee_id')
                    ->orderBy('created_at', 'desc');
            },
            'department'
        ])
            ->withCount(['services as assigned_services_count' => function($query) {
                $query->whereNotNull('employee_id');
            }])
            ->orderBy('name')
            ->paginate(10);

        return view('reports.employee_report', compact('employees'));
    }

    public function downloadEmployeeReport()
    {
        $employees = Employee::with([
            'department'
        ])
            ->withCount(['services as assigned_services_count' => function($query) {
                $query->whereNotNull('employee_id');
            }])
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('reports.employee_pdf', compact('employees'));

        return $pdf->download('employee_report_'.now()->format('Y-m-d').'.pdf');
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
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('booking_date', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->get();

        // Calculate costs based on filtered data
        $totalServiceCost = $query->sum('cost');
        $pendingServiceCost = (clone $query)->where('status', 'pending')->sum('cost');
        $completedServiceCost = (clone $query)->where('status', 'completed')->sum('cost');

        // Generate filename based on filters
        $filename = 'service_cost_report_'.now()->format('Y-m-d');
        if ($request->filled('start_date') || $request->filled('status')) {
            $filename .= '_filtered';
        }

        // Load the PDF view with filtered data
        $pdf = PDF::loadView('reports.cost_pdf', [
            'services' => $services,
            'totalServiceCost' => $totalServiceCost,
            'pendingServiceCost' => $pendingServiceCost,
            'completedServiceCost' => $completedServiceCost,
            'filters' => $request->only(['start_date', 'end_date', 'status'])
        ]);

        return $pdf->download($filename.'.pdf');
    }

}
