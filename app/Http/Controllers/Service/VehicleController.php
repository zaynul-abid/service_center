<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function search(Request $request)
    {
        $vehicles = Vehicle::where('vehicle_number', 'like', '%' . $request->vehicle_number . '%')
            ->get(['vehicle_number', 'vehicle_type', 'vehicle_company', 'vehicle_model', 'fuel_type']);

        if ($vehicles->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($vehicles);
    }



    public function getCompanies(Request $request)
    {
        $companyName = $request->input('company_name');

        $companies = Vehicle::where('vehicle_company', 'LIKE', '%' . $companyName . '%')
            ->distinct()
            ->pluck('vehicle_company');

        return response()->json($companies);
    }

    public function getVehicleModels(Request $request)
    {
        $searchTerm = $request->input('model_name');

        if (!empty($searchTerm)) {
            $vehicleModels = Vehicle::where('vehicle_model', 'LIKE', '%' . $searchTerm . '%')
                ->pluck('vehicle_model');

            return response()->json($vehicleModels);
        }

        return response()->json([]);
    }
}
