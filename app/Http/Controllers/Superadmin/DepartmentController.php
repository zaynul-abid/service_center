<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('superadmin.departments.index', compact('departments'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('superadmin.departments.create',compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')],
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $departmentData = [
                'name' => $request->name,
                'description' => $request->description,
            ];

            if (auth()->user()->usertype == 'founder') {
                $departmentData['company_id'] = $request->company_id; // Assign company_id from request if founder
            } else {
                $departmentData['company_id'] = auth()->user()->company_id; // Assign auth user's company_id
            }

            Department::create($departmentData);

            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create department: ' . $e->getMessage());
        }
    }
    public function edit(Department $department)
    {
        return view('superadmin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department->id)],
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $department->update($request->only(['name', 'description']));

            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update department: ' . $e->getMessage());
        }
    }

    public function destroy(Department $department)
    {
        DB::beginTransaction();
        try {
            $department->delete();
            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}
