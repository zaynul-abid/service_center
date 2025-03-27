<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
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
        return view('founder.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $this->validateCompanyData($request);

        DB::beginTransaction();

        try {
            $company = Company::create($validatedData);
            DB::commit();

            return redirect()
                ->route('companies.index')
                ->with([
                    'success' => 'Company created successfully!',
                    'company_id' => $company->id
                ]);

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create company: ' . $e->getMessage());
        }
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
}
