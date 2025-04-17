<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
use App\Models\SubscriptionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class FounderCompanyCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        $plans = DB::table('plans')->where('status', 1)->get(); // Fetch active plans

        return view('founder.companies.index', compact('companies', 'plans'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $plans =Plan::where('status',true)->get();
        return view('founder.companies.create',compact(
            'plans'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Basic validation
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20|regex:/^[\d\s\+-]+$/',
            'address' => 'required|string|min:10|max:500',
            'registration_number' => 'required|string|max:50|unique:companies',
            'plan_id' => 'required|exists:plans,id',
            'plan_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:'.$request->plan_amount,
            'final_price' => [
                'required',
                'numeric',
                'min:0',
                function ($attribute, $value, $fail) use ($request) {
                    $expected = $request->plan_amount - ($request->discount ?? 0);
                    if (abs($value - $expected) > 0.01) {
                        $fail('Final price calculation is incorrect');
                    }
                }
            ],
            'subscription_start_date' => 'required|date|after_or_equal:today',
            'subscription_end_date' => 'required|date|after:subscription_start_date',
        ]);



        DB::beginTransaction();

        try {
            $plan = Plan::findOrFail($validatedData['plan_id']);

            $company = Company::create([
                'company_name' => $validatedData['company_name'],
                'contact_number' => $validatedData['contact_number'],
                'address' => $validatedData['address'],
                'registration_number' => $validatedData['registration_number'],
                'plan_id' => $plan->id,
                'plan_amount' => $validatedData['plan_amount'],
                'plan_description' => $plan->name,
                'discount' => $validatedData['discount'] ?? 0,
                'final_price' => $validatedData['final_price'],
                'subscription_start_date' => $validatedData['subscription_start_date'],
                'subscription_end_date' => $validatedData['subscription_end_date'],
                'company_key' => Company::generateCompanyKey(),
                'status' => 'active'
            ]);


            SubscriptionHistory::create([
                'company_id' => $company->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'plan_amount' => $validatedData['plan_amount'],
                'discount' => $validatedData['discount'] ?? 0,
                'final_amount' => $validatedData['final_price'],
                'start_date' => $validatedData['subscription_start_date'],
                'end_date' => $validatedData['subscription_end_date'],
                'status' => 'active'
            ]);

            DB::commit();

            return redirect()->route('companies.index')
                ->with('success', 'Company created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Company creation error: '.$e->getMessage());
            return back()->withInput()->with('error', 'Error creating company: '.$e->getMessage());
        }
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        $plans = DB::table('plans')->where('status', 1)->get();
        return view('founder.companies.edit', compact('company','plans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20|regex:/^[\d\s\+-]+$/',
            'address' => 'required|string|min:10|max:500',
            'registration_number' => 'required|string|max:50|unique:companies,registration_number,' . $company->id,
            'plan_id' => 'required|exists:plans,id',
            'plan_amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'final_price' => 'required|numeric|min:0',
            'subscription_start_date' => 'required|date|after_or_equal:today',
            'subscription_end_date' => 'required|date|after:subscription_start_date',
        ], [
            'company_name.required' => 'Company name is required',
            'contact_number.regex' => 'Please enter a valid phone number',
            'address.min' => 'Address must be at least 10 characters',
            'registration_number.unique' => 'This registration number already exists',
            'plan_id.required' => 'Please select a subscription plan',
            'plan_amount.required' => 'Please enter the plan amount',
            'subscription_start_date.after_or_equal' => 'Start date cannot be in the past',
            'subscription_end_date.after' => 'End date must be after start date',
            'final_price.min' => 'Final price cannot be negative',
            'discount.numeric' => 'Discount must be a valid number.',
            'discount.min' => 'Discount cannot be negative.'
        ]);

        DB::beginTransaction();

        try {
            // Detect any meaningful change
            $subscriptionChanged =
                $company->plan_id != $validatedData['plan_id'] ||
                $company->subscription_start_date != $validatedData['subscription_start_date'] ||
                $company->subscription_end_date != $validatedData['subscription_end_date'] ||
                $company->final_price != $validatedData['final_price'] ||
                $company->discount != ($validatedData['discount'] ?? 0);

            // If changed, store in history
            if ($subscriptionChanged) {
                $plan = Plan::findOrFail($validatedData['plan_id']);

                SubscriptionHistory::create([
                    'company_id' => $company->id,
                    'company_name' => $company->company_name,
                    'contact_number' => $company->contact_number,
                    'address' => $company->address,
                    'registration_number' => $company->registration_number,
                    'company_key' => $company->company_key,

                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'plan_amount' => $plan->amount,
                    'plan_duration_days' => $plan->days,

                    'start_date' => $validatedData['subscription_start_date'],
                    'end_date' => $validatedData['subscription_end_date'],
                    'final_amount' => $validatedData['final_price'],
                    'discount' => $validatedData['discount'] ?? 0,
                    'status' => 'active',
                    'is_renewal' => false // This can be true if you're calling this a renewal
                ]);
            }

            // Update company
            $company->update([
                'company_name' => $validatedData['company_name'],
                'contact_number' => $validatedData['contact_number'],
                'address' => $validatedData['address'],
                'registration_number' => $validatedData['registration_number'],
                'plan_id' => $validatedData['plan_id'],
                'plan_amount' => $validatedData['plan_amount'],
                'discount' => $validatedData['discount'] ?? 0,
                'final_price' => $validatedData['final_price'],
                'subscription_start_date' => $validatedData['subscription_start_date'],
                'subscription_end_date' => $validatedData['subscription_end_date'],
                'status' => $this->checkSubscriptionStatus($validatedData['subscription_end_date']),
            ]);

            DB::commit();

            return redirect()->route('companies.index')
                ->with('success', 'Company updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Company update failed: ' . $e->getMessage());
            return back()->with('error', 'Company update failed: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // If the company is active, prevent deletion and show a warning
        if ($company->status === 'active') {
            return redirect()
                ->route('companies.index')
                ->with('warning', "Cannot delete '{$company->company_name}' because it is active.");
        }

        // If the company is expired, allow deletion but warn about data loss
        if ($company->status === 'expired') {
            $companyName = $company->company_name;

            // Optionally, delete related data here before deleting the company
            // Example: $company->employees()->delete();

            $company->delete();

            return redirect()
                ->route('companies.index')
                ->with('warning', "Company '{$companyName}' and all related data have been deleted.");
        }

        // Fallback message if status is something unexpected
        return redirect()
            ->route('companies.index')
            ->with('error', "Invalid company status for deletion.");
    }



    /**
     * Validate company data
     */


    private function checkSubscriptionStatus($endDate)
    {
        return now()->lessThan(Carbon::parse($endDate)) ? 'active' : 'expired';
    }



}
