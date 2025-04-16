<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class EmployeeAssignController extends Controller
{
    public function showAssignPage(Request $request)
    {
        try {
            $query = Service::query();


            // Apply filters if they exist in the request
            if ($request->ajax()) {
                if ($request->has('status') && $request->status) {
                    $query->where('status', $request->status);
                }

                if ($request->has('from_date') && $request->from_date) {
                    $query->whereDate('booking_date', '>=', $request->from_date);
                }

                if ($request->has('to_date') && $request->to_date) {
                    $query->whereDate('booking_date', '<=', $request->to_date);
                }

                if ($request->has('search') && $request->search) {
                    $search = $request->search;
                    $query->where(function($q) use ($search) {
                        $q->where('customer_name', 'like', "%$search%")
                            ->orWhere('contact_number_1', 'like', "%$search%")
                            ->orWhere('vehicle_number', 'like', "%$search%")
                            ->orWhere('booking_id', 'like', "%$search%")
                            ->orWhereHas('employee', function ($empQuery) use ($search) {
                                $empQuery->where('name', 'like', "%$search%");
                            });
                    });
                }
                $services = $query->paginate(10);
                return response()->json([
                    'html' => view('assign.table_rows', compact('services'))->render(),
                    'pagination' => (string)$services->links('pagination::bootstrap-5')
                ]);
            }

            $services = $query->paginate(10);
            $employees = Employee::all();

            return view('assign.assign', compact('services', 'employees'));
        } catch (Exception $e) {
            Log::error('Error loading assign page: ' . $e->getMessage());
            return $request->ajax()
                ? response()->json(['error' => 'Failed to load data'], 500)
                : redirect()->back()->with('error', 'Failed to load assign page.');
        }
    }


    public function assign(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'service_ids' => 'nullable|array',
                'service_ids.*' => 'exists:services,id'
            ]);

            if (!$request->has('service_ids') || empty($request->service_ids)) {
                return redirect()->back()->with('warning', 'Please select at least one service.');
            }

            // Assign services to the selected employee and update service status
            Service::whereIn('id', $request->service_ids)->update([
                'employee_id' => $request->employee_id,
                'service_status' => 'Requested'
            ]);

            // Update employee's assigned service status
            Employee::where('id', $request->employee_id)->update(['assigned_service_status' => 'Requested']);

            DB::commit();
            return redirect()->back()->with('success', 'Services assigned successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error assigning services: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to assign services.');
        }
    }
}
