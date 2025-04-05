<?php

namespace App\Http\Controllers\Followup;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Carbon\Carbon; // For date handling
class ServiceFollowUpController extends Controller
{
    public function followUp(Request $request)
    {
        try {
            $query = Service::with('employee')
                ->selectRaw('services.*, DATEDIFF(expected_delivery_date, CURDATE()) as days_difference');

            // Apply search filter if provided
            if ($request->filled('search_days')) {
                $searchDays = (int) $request->input('search_days');

                if ($searchDays === 0) {
                    $query->whereRaw('expected_delivery_date < CURDATE()'); // Overdue
                } else {
                    $query->whereRaw('DATEDIFF(expected_delivery_date, CURDATE()) = ?', [$searchDays]);
                }
            }

            // Order by days difference (overdue first, then upcoming)
            $services = $query->orderBy('days_difference')->get();

            return view('admin.pages.follow', compact('services'));
        } catch (\Exception $e) {
            \Log::error('Follow-up error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to fetch follow-ups');
        }
    }
}
