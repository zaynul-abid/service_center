<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Plan;
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
        $companies = Company::orderBy('created_at', 'desc')->get();
        return view('founder.companies.index', compact('companies'));
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
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20|regex:/^[\d\s\+-]+$/',
            'address' => 'required|string|min:10|max:500',
            'registration_number' => 'required|string|max:50|unique:companies',
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

        // Create the company with the validated data
        Company::create([
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
            'company_key' => Company::generateCompanyKey(),
            'status' => $this->checkSubscriptionStatus($validatedData['subscription_end_date'])
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('founder.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validatedData = $this->validateCompanyData($request, $company->id);

        DB::beginTransaction();

        try {
            $company->update($validatedData);
            DB::commit();

            return redirect()
                ->route('companies.index')
                ->with('success', 'Company updated successfully!');

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update company: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        DB::beginTransaction();

        try {
            $companyName = $company->company_name;
            $company->delete();
            DB::commit();

            return redirect()
                ->route('companies.index')
                ->with('success', "Company '{$companyName}' deleted successfully!");

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to delete company: ' . $e->getMessage());
        }
    }

    /**
     * Validate company data
     */
    protected function validateCompanyData(Request $request, $companyId = null)
    {
        return $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20|regex:/^[\d\s\+-]+$/',
            'address' => 'required|string|min:10|max:500',
            'registration_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('companies')->ignore($companyId)
            ],
            'plan' => 'required|string', // Example plan types
            'subscription_start_date' => 'required|date|after_or_equal:today',
            'subscription_end_date' => 'required|date|after:subscription_start_date',
        ], [
            'company_name.required' => 'Please provide the company name',
            'contact_number.regex' => 'Please enter a valid phone number',
            'address.min' => 'Address should be at least 10 characters',
            'registration_number.unique' => 'This registration number is already in use',
            'subscription_start_date.after_or_equal' => 'Start date cannot be in the past',
            'subscription_end_date.after' => 'End date must be after start date',
            'plan.in' => 'Please select a valid plan type'
        ]);
    }
    private function checkSubscriptionStatus($endDate)
    {
        return now()->lessThan(Carbon::parse($endDate)) ? 'active' : 'expired';
    }

    public function updateStatus(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        // Update status
        $status = $request->input('status');
        $company->status = $status;

        // Generate and update company key on renewal
        if ($status === 'active') {
            $company->company_key = 'CK-' . strtoupper(Str::random(10));  // Generate a unique company key
        }

        $company->save();

        return back()->with('success', 'Company status and key updated successfully!');
    }

}
