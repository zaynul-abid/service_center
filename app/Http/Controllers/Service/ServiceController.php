<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::all(); // Fetch all service records

        return view('services.pages.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::all();
        return view('registration-form.create',compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $user = Auth::user();

        // Validation
        $request->validate([
            'vehicle_number' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_company' => 'required|string|max:100',
            'vehicle_model' => 'required|string|max:100',
            'fuel_type' => 'required|string|max:50',
            'fuel_level' => 'nullable|integer|min:0|max:100',
            'km_driven' => 'nullable|integer|min:0',
            'customer_name' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'contact_number_1' => 'required|string|max:15',
            'contact_number_2' => 'nullable|string|max:15',
            'reference_number' => 'nullable|string|max:100',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'customer_complaint' => 'nullable|string',
            'service_details' => 'nullable|string',
            'remarks' => 'nullable|string|max:500',
            'cost' => 'nullable|numeric|min:0',
            'expected_delivery_date' => 'nullable|date',
            'expected_delivery_time' => 'nullable|date_format:H:i',
            'company_id' => 'nullable|exists:companies,id',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',  // Validate each photo in the array
        ]);

        // Handle Vehicle Creation or Update
        $vehicle = Vehicle::firstOrCreate(
            ['vehicle_number' => $request->vehicle_number],
            $request->only([
                'vehicle_type',
                'vehicle_company',
                'vehicle_model',
                'fuel_type',
                'fuel_level',
                'km_driven',
            ])
        );

        // Handle Customer Creation or Update
        $customer = Customer::firstOrCreate(
            ['contact_number_1' => $request->contact_number_1],
            $request->only([
                'customer_name',
                'place',
                'contact_number_2',
            ])
        );

        // Handle Photos Upload
        $photosArray = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                $photosArray[] = $path;
            }
        }

        // Generate the Next Ordered and Small Booking ID
        $lastService = Service::withTrashed()->orderBy('id', 'desc')->first();  // Includes soft-deleted records
        $nextBookingNumber = $lastService ? $lastService->id + 1 : 1;  // Increment by 1
        $nextBookingId = 'B-' . $nextBookingNumber;

        $companyId = $user->usertype == 'founder' ? $request->company_id : $user->company_id;

        // Create the Service Entry
        Service::create([
            'vehicle_number' => $request->vehicle_number,
            'contact_number_1' => $request->contact_number_1,
            'customer_name' => $request->customer_name,
            'place' => $request->place,
            'contact_number_2' => $request->contact_number_2,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_company' => $request->vehicle_company,
            'vehicle_model' => $request->vehicle_model,
            'fuel_type' => $request->fuel_type,
            'fuel_level' => $request->fuel_level,
            'km_driven' => $request->km_driven,
            'booking_id' => $nextBookingId,
            'reference_number' => $request->reference_number,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'customer_complaint' => $request->customer_complaint,
            'service_details' => $request->service_details,
            'remarks' => $request->remarks,
            'cost' => $request->cost,
            'expected_delivery_date' => $request->expected_delivery_date,
            'expected_delivery_time' => $request->expected_delivery_time,
            'company_id' => $companyId,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'photos' => json_encode($photosArray),
        ]);

        // Redirect with Success Message
        $redirectRoute = $user->isEmployee() ? 'employee.dashboard' : 'services.index';
        return redirect()->route($redirectRoute)->with('success', 'Service record created successfully.');
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
    public function edit($id)
    {
        // Find the service record by ID
        $service = Service::findOrFail($id);


        // Pass the service record to the edit view
        return view('registration-form.edit', compact('service'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $user = Auth::user();

        // Find the service record
        $service = Service::findOrFail($id);

        // Validation (same as store)
        $request->validate([
            'vehicle_number' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:100',
            'vehicle_company' => 'required|string|max:100',
            'vehicle_model' => 'required|string|max:100',
            'fuel_type' => 'required|string|max:50',
            'fuel_level' => 'nullable|integer|min:0|max:100',
            'km_driven' => 'nullable|integer|min:0',
            'customer_name' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'contact_number_1' => 'required|string|max:15',
            'contact_number_2' => 'nullable|string|max:15',
            'reference_number' => 'nullable|string|max:100',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'customer_complaint' => 'nullable|string',
            'service_details' => 'nullable|string',
            'remarks' => 'nullable|string|max:500',
            'cost' => 'nullable|numeric|min:0',
            'expected_delivery_date' => 'nullable|date',
            'expected_delivery_time' => 'required',
            'company_id' => 'nullable|exists:companies,id',
            'photos.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'nullable|string|max:50', // Added status field for update
        ]);

        // Handle Vehicle Update
        $vehicle = Vehicle::firstOrCreate(
            ['vehicle_number' => $request->vehicle_number],
            $request->only([
                'vehicle_type',
                'vehicle_company',
                'vehicle_model',
                'fuel_type',
                'fuel_level',
                'km_driven',
            ])
        );

        // Handle Customer Update
        $customer = Customer::firstOrCreate(
            ['contact_number_1' => $request->contact_number_1],
            $request->only([
                'customer_name',
                'place',
                'contact_number_2',
            ])
        );

        // Handle Photos Upload (append to existing photos)
        $photosArray = json_decode($service->photos, true) ?? [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('photos', 'public');
                $photosArray[] = $path;
            }
        }

        // Handle photo deletions if needed (you would need to pass deleted photo IDs in request)
        if ($request->has('deleted_photos')) {
            $photosArray = array_diff($photosArray, $request->deleted_photos);
            // Optionally delete the files from storage here
        }

//        $companyId = $user->usertype == 'founder' ? $request->company_id : $user->company_id;

        // Update the Service Entry
        $service->update([
            'vehicle_number' => $request->vehicle_number,
            'contact_number_1' => $request->contact_number_1,
            'customer_name' => $request->customer_name,
            'place' => $request->place,
            'contact_number_2' => $request->contact_number_2,
            'vehicle_type' => $request->vehicle_type,
            'vehicle_company' => $request->vehicle_company,
            'vehicle_model' => $request->vehicle_model,
            'fuel_type' => $request->fuel_type,
            'fuel_level' => $request->fuel_level,
            'km_driven' => $request->km_driven,
            'reference_number' => $request->reference_number,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'customer_complaint' => $request->customer_complaint,
            'service_details' => $request->service_details,
            'remarks' => $request->remarks,
            'cost' => $request->cost,
            'expected_delivery_date' => $request->expected_delivery_date,
            'expected_delivery_time' => $request->expected_delivery_time,
            'company_id' => $request->company_id,
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customer->id,
            'photos' => json_encode($photosArray),
            'status' => $request->status ?? $service->status, // Update status if provided
        ]);

        // Redirect with Success Message
        $redirectRoute = $user->isEmployee() ? 'employee.dashboard' : 'services.index';
        return redirect()->route($redirectRoute)->with('success', 'Service record updated successfully.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);

        try {
            $service->delete(); // Soft delete or permanent delete based on your setup
            return redirect()->route('services.index')->with('success', 'Service record deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('services.index')->with('error', 'Failed to delete service record: ' . $e->getMessage());
        }
    }
}
