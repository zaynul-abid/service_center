<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = Plan::latest()->paginate(10);
        return view('founder.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('founder.plans.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            Plan::create([
                'name' => $request->input('name'),
                'amount' => $request->input('amount'),
                'days' => $request->input('days'),
                'status' => $request->input('status', 'active') // Default to 'active' if not provided
            ]);

            return redirect()->route('plans.index')
                ->with('success', 'Plan created successfully!');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating plan: '.$e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( Plan $plan)
    {
        return view('founder.plans.edit', compact('plan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'days' => 'required|integer|min:1'
        ]);

        $plan->update($request->all());

        return redirect()->route('plans.index')
            ->with('success', 'Plan updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return redirect()->route('plans.index')
            ->with('success', 'Plan deleted successfully.');
    }
}
