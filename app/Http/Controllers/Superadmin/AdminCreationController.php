<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class AdminCreationController extends Controller
{
    public function index()
    {
        $admins = User::where('usertype', 'admin')->get();
        return view('superadmin.admin.index', compact('admins'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('superadmin.admin.create',compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')],
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::beginTransaction();
        try {
            $superAdmin = auth()->user(); // Get logged-in Super Admin



            $admin = new User();
            $admin->name = $request->name;
            $admin->email = $request->email;
            $admin->password = Hash::make($request->password);
            if (auth()->user()->usertype == 'founder') {
                $admin->company_id = $request->company_id;  // Corrected assignment
            } else {
                $admin->company_id = $superAdmin->company_id;
            }
            $admin->usertype = 'admin';
            $admin->save();

            DB::commit();
            return redirect()->route('superadmin-admins.index')->with('success', 'Admin user created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create admin: ' . $e->getMessage());
        }
    }

    public function edit(User $superadmin_admin)
    {

        $companies = Company::all();
        return view('superadmin.admin.edit', compact('superadmin_admin','companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($id), // Ignore current user's email
            ],
            'password' => 'nullable|string|min:6|confirmed', // Password is optional
        ]);

        DB::beginTransaction();
        try {
            $superAdmin = auth()->user(); // Get logged-in Super Admin
            $admin = User::findOrFail($id); // Find the admin to update

            // Update basic details
            $admin->name = $request->name;
            $admin->email = $request->email;

            // Update password only if provided
            if ($request->filled('password')) {
                $admin->password = Hash::make($request->password);
            }

            // Update company_id based on user type
            if (auth()->user()->usertype == 'founder') {
                $admin->company_id = $request->company_id;
            } else {
                $admin->company_id = $superAdmin->company_id;
            }

            $admin->save();

            DB::commit();
            return redirect()->route('superadmin-admins.index')->with('success', 'Admin user updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update admin: ' . $e->getMessage());
        }
    }

    public function destroy(User $superadmin_admin)
    {
        DB::beginTransaction();
        try {
            $superadmin_admin->delete();
            DB::commit();
            return redirect()->route('superadmin-admins.index')->with('success', 'Admin deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete admin: ' . $e->getMessage());
        }
    }
}
