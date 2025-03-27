<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::paginate(10);
        return view('superadmin.employees.index', compact('employees'));
    }

    public function create()
    {
        $companies = Company::all();

        $departments = Department::all();
        return view('superadmin.employees.create', compact('departments','companies'));
    }

    public function store(Request $request)
    {
        // Validation (without company_id)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|min:6|confirmed',
            'department_id' => 'required|exists:departments,id',
            'position' => 'nullable|string|max:100',
        ]);

        DB::beginTransaction();

        try {
            // Assign company_id dynamically based on usertype
            $company_id = auth()->user()->usertype == 'founder' ? $request->company_id : auth()->user()->company_id;

            // Create the User record first
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'company_id' => $company_id,
                'usertype' => 'employee',
            ]);

            // Check if user creation failed
            if (!$user) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Failed to create User record. Please try again.');
            }

            // Log the user ID (for debugging purposes)
            Log::info('User created with ID: ' . $user->id);

            // Create the Employee record and link the user_id from the newly created user
            Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'department_id' => $request->department_id,
                'position' => $request->position,
                'password' => Hash::make($request->password), // Hashed password for employees table
                'company_id' => $company_id,
                'user_id' => $user->id,  // Linking the user_id correctly
            ]);

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create employee: ' . $e->getMessage());
        }
    }



    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('superadmin.employees.edit', compact('employee', 'departments'));
    }
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Validation
            $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:employees,email,' . $id,
                'phone'         => 'nullable|regex:/^\d{10,15}$/',
                'department_id' => 'required|exists:departments,id',
                'position'      => 'nullable|string|max:255',
                'password'      => 'nullable|string|min:6|confirmed',
            ]);

            // Find the employee or fail
            $employee = Employee::findOrFail($id);


            // Update Employee record
            $employee->update([
                'name'          => $request->name,
                'email'         => $request->email,
                'phone'         => $request->phone,
                'department_id' => $request->department_id,
                'position'      => $request->position,
            ]);

            // Update password only if provided
            if ($request->filled('password')) {
                $employee->update(['password' => Hash::make($request->password)]);
            }

            DB::commit();
            return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(Employee $employee)
    {



        // Delete the related User record
        $employee->user()->delete();

        // Then delete the Employee record
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
