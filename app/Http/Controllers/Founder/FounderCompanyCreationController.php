<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class FounderCompanyCreationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();

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
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20|regex:/^[0-9+\s-]+$/',
            'address' => 'required|string|min:10',
            'registration_number' => 'required|string|unique:companies|max:50',
            'plan' => 'required|string',
            'subscription_start_date' => 'required|date',
            'subscription_end_date' => 'required|date|after:subscription_start_date',
        ], [
            'company_name.required' => 'The company name is required.',
            'contact_number.required' => 'The contact number is required.',
            'contact_number.regex' => 'The contact number format is invalid.',
            'address.required' => 'The address is required and should be at least 10 characters long.',
            'registration_number.required' => 'The registration number is required.',
            'registration_number.unique' => 'This registration number already exists.',
            'subscription_end_date.after' => 'The subscription end date must be after the start date.',
        ]);

        DB::beginTransaction();

        try {
            Company::create($validatedData);
            DB::commit();

            return redirect()->route('companies.index')->with('success', 'Company created successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating company: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $validatedData = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20|regex:/^[0-9+\s-]+$/',
            'address' => 'required|string|min:10',
            'registration_number' => 'required|string|max:50|unique:companies,registration_number,' . $company->id,
            'plan' => 'required|string',
            'subscription_start_date' => 'required|date',
            'subscription_end_date' => 'required|date|after:subscription_start_date',
        ], [
            'company_name.required' => 'The company name is required.',
            'contact_number.required' => 'The contact number is required.',
            'contact_number.regex' => 'The contact number format is invalid.',
            'address.required' => 'The address is required and should be at least 10 characters long.',
            'registration_number.required' => 'The registration number is required.',
            'registration_number.unique' => 'This registration number already exists.',
            'subscription_end_date.after' => 'The subscription end date must be after the start date.',
        ]);

        DB::beginTransaction();

        try {
            $company->update($validatedData);
            DB::commit();

            return redirect()->route('companies.index')->with('success', 'Company updated successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error updating company: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        DB::beginTransaction();

        try {
            $company->delete();
            DB::commit();

            return redirect()->route('companies.index')->with('success', 'Company deleted successfully.');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting company: ' . $e->getMessage());
        }
    }
}
