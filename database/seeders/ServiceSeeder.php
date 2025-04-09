<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $vehicleTypes = ['two wheeler', 'three wheeler', 'four wheeler', 'heavy'];
        $vehicleCompanies = ['Toyota', 'Honda', 'Tata', 'Suzuki', 'Mahindra', 'Hyundai', 'Ashok Leyland'];
        $vehicleModels = ['Innova', 'Activa', 'Nexon', 'Swift', 'Bolero', 'i20', 'Dost'];
        $fuelTypes = ['petrol', 'diesel', 'electric','cng','other'];
        $complaints = [
            'Strange noise from engine', 'Brake failure', 'AC not working', 'Low mileage', 'Steering vibration',
            'Overheating issue', 'Battery draining', 'Oil leakage', 'Headlight malfunction', 'Gear shifting problem'
        ];
        $serviceDetails = [
            'Engine check & oil change', 'Brake pad replacement', 'Full body wash', 'Clutch adjustment',
            'AC maintenance', 'Battery replacement', 'Wheel alignment', 'Tire rotation', 'Transmission repair',
            'Coolant flush'
        ];

        for ($i = 0; $i < 500; $i++) {
            // 1. Create customer
            $customer = Customer::create([
                'customer_name' => 'Customer ' . Str::random(5),
                'contact_number_1' => '98' . rand(10000000, 99999999),
                'contact_number_2' => '97' . rand(10000000, 99999999),
                'place' => 'Place ' . rand(1, 50),
            ]);

            // 2. Create vehicle
            $vehicle = Vehicle::create([
                'vehicle_number' => strtoupper(Str::random(2)) . '-' . rand(1000, 9999),
                'vehicle_type' => $vehicleTypes[array_rand($vehicleTypes)],
                'vehicle_company' => $vehicleCompanies[array_rand($vehicleCompanies)],
                'vehicle_model' => $vehicleModels[array_rand($vehicleModels)],
                'fuel_type' => $faker->randomElement(['petrol', 'diesel', 'electric', 'cng','other']),
                // ✅ Add this line
            ]);

            // 3. Create service
            Service::create([
                'booking_id' => 'B-' . $i,
                'reference_number' => 'SRV-' . strtoupper(Str::random(6)),
                'booking_date' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'booking_time' => now()->format('H:i:s'),
                'fuel_type' => $fuelTypes[array_rand($fuelTypes)],
                'fuel_level' => rand(10, 100),
                'km_driven' => rand(1000, 50000),
                'status' => 'pending',
                'service_status' => 'pending',
                'cost' => rand(500, 10000),
                'service_details' => $serviceDetails[array_rand($serviceDetails)] . ' ' . Str::random(5),
                'customer_complaint' => $complaints[array_rand($complaints)] . ' ' . Str::random(4),
                'expected_delivery_date' => now()->addDays(rand(1, 7))->format('Y-m-d'),
                'expected_delivery_time' => now()->addHours(rand(1, 8))->format('H:i:s'),
                'company_id' => 1,
                'vehicle_id' => $vehicle->id,
                'vehicle_number' => $vehicle->vehicle_number,
                'vehicle_type' => $vehicle->vehicle_type,
                'vehicle_company' => $vehicle->vehicle_company,
                'vehicle_model' => $vehicle->vehicle_model,
                'customer_id' => $customer->id,
                'employee_id' => null,
                'employee_remarks' => null,
                'customer_name' => $customer->customer_name,
                'contact_number_1' => $customer->contact_number_1,
                'contact_number_2' => $customer->contact_number_2,
            ]);
        }
    }
}
