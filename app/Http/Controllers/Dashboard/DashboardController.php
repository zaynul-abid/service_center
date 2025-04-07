<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function founderIndex(){

        $companyCount = Company::count();
        $adminCount = User::where('usertype', 'admin')->count();
        $employeeCount = User::where('usertype', 'employee')->count();
        $superadminCount = User::where('usertype', 'superadmin')->count();
        $users = User::all()->except(auth()->id());
        return view('founder.pages.dashboard',compact('users','adminCount','employeeCount','superadminCount','companyCount'));
    }

    public function superadminIndex(){
        $pendingServiceCount = Service::where('status', 'pending')->count();
        $completedServiceCount = Service::where('status', 'completed')->count();
        $adminCount = User::where('usertype', 'admin')->count();
        $employeeCount = User::where('usertype', 'employee')->count();
        $users = User::where('usertype', '!=', 'founder')->where('id', '!=', auth()->id())->get();
        return view('superadmin.pages.dashboard',compact('users','adminCount','employeeCount','pendingServiceCount','completedServiceCount'));
    }

    public function adminIndex(){
        $pendingServiceCount = Service::where('status', 'pending')->count();
        $completedServiceCount = Service::where('status', 'completed')->count();
        $serviceCount = Service::all()->count();
        $employeeCount = User::where('usertype', 'employee')->count();
        $users = User::where('id', '!=', auth()->id())
        ->whereNotIn('usertype', ['founder', 'superadmin'])
        ->get();
        return view('admin.pages.dashboard',compact('pendingServiceCount','completedServiceCount','users','employeeCount','serviceCount'));
    }

    public function employeeIndex()
    {
        $employee = Employee::where('user_id', auth()->user()->id)->first();

        if ($employee) {
            $assignedServices = $employee->assignedServices;
        } else {
            $assignedServices = collect();
        };

        return view('employees.pages.dashboard', compact('assignedServices', 'employee'));
    }


}
