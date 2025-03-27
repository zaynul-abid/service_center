<?php

namespace App\Http\Controllers\service;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function search(Request $request)
    {
        $searchTerm = $request->input('customer_name');  // Get input from AJAX request

        // Fetch matching customers (adjust the query as per your database fields)
        $customers = Customer::where('customer_name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('contact_number_1', 'LIKE', '%' . $searchTerm . '%')
            ->get();

        // Return the result as JSON response
        return response()->json($customers);
    }
}
