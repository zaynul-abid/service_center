<?php

namespace App\Http\Controllers\Founder;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class FounderSuperadminController extends Controller
{
    public function index()
    {
        $superadmins = User::where('usertype', 'superadmin')->get();
        return view('founder.superadmins.index', compact('superadmins'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('founder.superadmins.create', compact('companies'));
    }



    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|min:6|confirmed',
            'company_id' => 'required|exists:companies,id',
        ]);

        DB::beginTransaction();
        Log::info('Transaction started for creating Superadmin.');

        try {
            // Create new user
            $user = User::create([
                'name'       => $validatedData['name'],
                'email'      => $validatedData['email'],
                'password'   => Hash::make($validatedData['password']),
                'usertype'   => 'superadmin',
                'company_id' => $validatedData['company_id'],
            ]);

            Log::info('User created successfully: ', ['user_id' => $user->id]);

            DB::commit();
            Log::info('Transaction committed successfully.');

            return redirect()
                ->route('superadmins.index')
                ->with('success', 'Superadmin created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transaction rolled back due to an error: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }





    public function edit(User $superadmin)
    {
        $companies = Company::all();
        return view('founder.superadmins.edit', compact('superadmin', 'companies'));
    }

    public function update(Request $request, User $superadmin)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($superadmin->id)],
            'company_id' => 'required|exists:companies,id'
        ]);

        DB::beginTransaction(); // Start Transaction

        try {
            $superadmin->update([
                'name' => $request->name,
                'email' => $request->email,
                'company_id' => $request->company_id
            ]);

            DB::commit(); // Commit Transaction

            return redirect()->route('superadmins.index')->with('success', 'Superadmin updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback Transaction on Error
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }

    public function destroy(User $superadmin)
    {
        DB::beginTransaction(); // Start Transaction

        try {
            $superadmin->delete();

            DB::commit(); // Commit Transaction

            return redirect()->route('superadmins.index')->with('success', 'Superadmin deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback Transaction on Error
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
}
