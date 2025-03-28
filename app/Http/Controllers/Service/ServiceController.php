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
        // Validate the incoming request data
        $validatedData = $request->validate([
            'vehicle_number' => 'required|string|max:255',
            'vehicle_type' => 'required|string|max:255',
            'vehicle_company' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'fuel_type' => 'required|string|max:100',
            'fuel_level' => 'nullable|numeric|min:0|max:100',
            'km_driven' => 'nullable|numeric',
            'contact_number_1' => 'required|string|max:15',
            'customer_name' => 'required|string|max:255',
            'place' => 'nullable|string|max:255',
            'contact_number_2' => 'nullable|string|max:15',
            'reference_number' => 'nullable|string|max:255',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'customer_complaint' => 'nullable|string',
            'service_details' => 'nullable|string',
            'remarks' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'expected_delivery_date' => 'required|date',
            'expected_delivery_time' => 'required|date_format:H:i',
            'status' => 'required|string|in:pending,in_progress,completed,cancelled', // Added status field
            'photos.*' => 'nullable|image|max:2048', // Validate photos if uploaded
        ]);

        $user = Auth::user();

        if (!$user->company_id) {
            return redirect()->route('services.index')->with('error', 'You do not have permission to update a service record.');
        }

        // Begin database transaction
        DB::beginTransaction();
        try {
            // Find the service record to update
            $service = Service::findOrFail($id);

            // Update or Create Vehicle Data
            $vehicle = Vehicle::updateOrCreate(
                ['vehicle_number' => $validatedData['vehicle_number']],
                [
                    'vehicle_type' => $validatedData['vehicle_type'],
                    'vehicle_company' => $validatedData['vehicle_company'],
                    'vehicle_model' => $validatedData['vehicle_model'],
                    'fuel_type' => $validatedData['fuel_type'],
                    'fuel_level' => $validatedData['fuel_level'],
                    'km_driven' => $validatedData['km_driven'],
                ]
            );

            // Update or Create Customer Data
            $customer = Customer::updateOrCreate(
                ['contact_number_1' => $validatedData['contact_number_1']],
                [
                    'customer_name' => $validatedData['customer_name'],
                    'place' => $validatedData['place'],
                    'contact_number_2' => $validatedData['contact_number_2'],
                ]
            );

            // Handle Photo Uploads
            $photosArray = [];
            if ($request->hasFile('photos')) {
                // Delete old photos if necessary (optional)
                if ($service->photos) {
                    foreach (json_decode($service->photos) as $oldPhoto) {
                        Storage::disk('public')->delete($oldPhoto);
                    }
                }

                // Store new photos
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('photos', 'public');
                    $photosArray[] = $path;
                }
            } else {
                // Retain existing photos if no new photos are uploaded
                $photosArray = json_decode($service->photos, true) ?? [];
            }

            // Update Service Data
            $service->update([
                'vehicle_number' => $validatedData['vehicle_number'],
                'contact_number_1' => $validatedData['contact_number_1'],
                'customer_name' => $validatedData['customer_name'],
                'place' => $validatedData['place'],
                'contact_number_2' => $validatedData['contact_number_2'],
                'vehicle_type' => $validatedData['vehicle_type'],
                'vehicle_company' => $validatedData['vehicle_company'],
                'vehicle_model' => $validatedData['vehicle_model'],
                'fuel_type' => $validatedData['fuel_type'],
                'fuel_level' => $validatedData['fuel_level'],
                'km_driven' => $validatedData['km_driven'],
                'reference_number' => $validatedData['reference_number'],
                'booking_date' => $validatedData['booking_date'],
                'booking_time' => $validatedData['booking_time'],
                'customer_complaint' => $validatedData['customer_complaint'],
                'service_details' => $validatedData['service_details'],
                'remarks' => $validatedData['remarks'],
                'cost' => $validatedData['cost'],
                'expected_delivery_date' => $validatedData['expected_delivery_date'],
                'expected_delivery_time' => $validatedData['expected_delivery_time'],
                'status' => $validatedData['status'], // Added status field
                'company_id' => $user->company_id,
                'vehicle_id' => $vehicle->id,
                'customer_id' => $customer->id,
                'photos' => json_encode($photosArray), // Update photos
            ]);

            // Commit the transaction
            DB::commit();

            return redirect()->route('services.index')->with('success', 'Service record updated successfully.');
        } catch (\Exception $e) {
            // Rollback the transaction if any error occurs
            DB::rollBack();
            return redirect()->route('services.index')->with('error', 'Failed to update service record: ' . $e->getMessage());
        }
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
