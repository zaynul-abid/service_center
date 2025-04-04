<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\SubscriptionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyRenewalController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $status = $request->input('status');
        $previousStatus = $company->status;
        $company->status = $status;

        // Redirect to renewal page if changing from expired to active
        if ($status === 'active' && $previousStatus === 'expired') {
            return redirect()->route('companies.renew', $company->id)
                ->with('company', $company);
        } elseif ($status === 'active') {
            $company->company_key = 'CK-' . strtoupper(Str::random(10));
        }

        $company->save();

        return back()->with('success', 'Company status updated successfully!');
    }

    public function showRenewalForm(Company $company)
    {
        $plans = \DB::table('plans')->select('id', 'name', 'amount', 'days')->get();
        return view('founder.companies.renew', compact('company', 'plans'));
    }

    public function processRenewal(Request $request, Company $company)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'subscription_start_date' => 'required|date',
            'subscription_end_date' => 'required|date|after:subscription_start_date',
            'plan_amount' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $plan = Plan::findOrFail($validated['plan_id']);


            // Create comprehensive history record
         $a=   SubscriptionHistory::create([
                // Company Snapshot
                'company_id' => $company->id,
                'company_name' => $company->company_name,
                'contact_number' => $company->contact_number,
                'address' => $company->address,
                'registration_number' => $company->registration_number,
                'company_key' => $company->company_key,

                // Plan Snapshot
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'plan_amount' => $plan->amount,
                'plan_duration_days' => $plan->days,

                // Subscription Details
                'start_date' => $validated['subscription_start_date'],
                'end_date' => $validated['subscription_end_date'],
                'final_amount' => $validated['final_price'],
                'discount' => $validated['discount'],
                'status' => 'active',
                'is_renewal' => true
            ]);



            // Update existing company
            $company->update([
                'company_key' => 'CK-'.strtoupper(Str::random(10)),
                'status' => 'active',
                'plan_id' => $plan->id,
                'plan_amount' => $validated['plan_amount'],
                'discount' => $validated['discount'],
                'final_price' => $validated['final_price'],
                'subscription_start_date' => $validated['subscription_start_date'],
                'subscription_end_date' => $validated['subscription_end_date']
            ]);

            DB::commit();

            return redirect()->route('companies.index', $company->id)
                ->with('success', 'Company renewed successfully with complete history tracking!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Renewal failed: '.$e->getMessage());
            return back()->with('error', 'Renewal failed: '.$e->getMessage());
        }
    }
}
