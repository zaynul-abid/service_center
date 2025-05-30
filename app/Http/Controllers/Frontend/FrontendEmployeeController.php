<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Service;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontendEmployeeController extends Controller
{


//    public function index()
//    {
//        $employee = Employee::where('user_id', auth()->user()->id)->first();
//
//        if ($employee) {
//            $assignedServices = $employee->assignedServices; // Get assigned services
//        } else {
//            $assignedServices = collect(); // Empty collection if no employee found
//        };
//
//
//
//        return view('employees.pages.dashboard', compact('assignedServices', 'employee'));
//    }




    public function destroy(Request $request)
    {
        Auth::logout(); // Log the user out

        // Invalidate session and regenerate token to prevent CSRF issues
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to login page after logout
        return redirect()->route('login')->with('success', 'You have been logged out successfully.');
    }

    public function showStatus()
    {
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        if ($employee) {
            $assignedServices = $employee->assignedServices()->paginate(10); // Use relationship query
            \Log::info('Assigned services fetched: ', $assignedServices->items()); // Debug log
        } else {
            $assignedServices = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10); // Empty paginator
            \Log::warning('No employee found for user ID: ' . auth()->user()->id); // Debug log
        }

        return view('employees.pages.status', compact('assignedServices', 'employee'));
    }
    public function updateStatus(Request $request, $id)
    {


//        $request->validate([
//            'status' => 'required|string',
//            'employee_remarks' => 'nullable|string|max:500',
//        ]);


        try {
            $service = Service::findOrFail($id);

            $service->service_status = $request->status;
            $service->save();

            return redirect()->back()->with('success', 'Service status and notes updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update service: ' . $e->getMessage());
        }
    }
    public function updateNotes(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'employee_remarks' => 'nullable|string',
            'technician_notes' => 'nullable|string',
        ]);

        try {
            $service = Service::findOrFail($request->service_id);
            $service->employee_remarks = $request->employee_remarks;
            $service->technician_notes = $request->technician_notes;
            $service->save();

            // Return a successful response
            return response()->json(['success' => true, 'message' => 'Notes updated successfully!']);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Failed to update notes', ['error' => $e->getMessage(), 'service_id' => $request->service_id]);

            // Return failure response
            return response()->json(['success' => false, 'message' => 'Failed to update notes: ' . $e->getMessage()]);
        }
    }
}
