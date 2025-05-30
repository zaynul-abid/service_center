<?php

namespace App\Http\Controllers\Softdelete;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Plan;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class SoftDeleteController extends Controller
{
    public function index(Request $request)
    {
        $activeTab = $request->get('tab', 'users');

        $deletedUsers = User::onlyTrashed()->paginate(10, ['*'], 'users_page');
        $deletedEmployees = Employee::onlyTrashed()->paginate(10, ['*'], 'employees_page');
        $deletedCustomers = Customer::onlyTrashed()->paginate(10, ['*'], 'customers_page');
        $deletedDepartments = Department::onlyTrashed()->paginate(10, ['*'], 'departments_page');
        $deletedServices = Service::onlyTrashed()->paginate(10, ['*'], 'services_page');
        $deletedVehicles = Vehicle::onlyTrashed()->paginate(10, ['*'], 'vehicles_page');
        $deletedPlans = Plan::onlyTrashed()->paginate(10, ['*'], 'plans_page');
        $deletedCompanies = Company::onlyTrashed()->paginate(10, ['*'], 'companies_page');

        return view('softdelete.recover_deleted', compact(
            'deletedUsers',
            'deletedEmployees',
            'deletedCustomers',
            'deletedDepartments',
            'deletedServices',
            'deletedVehicles',
            'deletedPlans',
            'deletedCompanies',
            'activeTab'
        ));
    }

    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        \DB::transaction(function () use ($user) {
            $user->restore();

            if ($employee = Employee::onlyTrashed()->where('user_id', $user->id)->first()) {
                $employee->restore();
            }
        });

        return redirect()->route('softdelete.index', ['tab' => 'users'])
            ->with('success', 'User and associated employee restored successfully');
    }

    public function forceDeleteUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        \DB::transaction(function () use ($user) {
            if ($employee = Employee::onlyTrashed()->where('user_id', $user->id)->first()) {
                $employee->forceDelete();
            }

            $user->forceDelete();
        });

        return redirect()->route('softdelete.index', ['tab' => 'users'])
            ->with('success', 'User permanently deleted');
    }

    public function restoreEmployee($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);

        \DB::transaction(function () use ($employee) {
            $employee->restore();

            // Restore the related user if soft-deleted
            if ($employee->user_id) {
                $user = User::onlyTrashed()->find($employee->user_id);
                if ($user) {
                    $user->restore();
                }
            }
        });

        return redirect()->route('softdelete.index', ['tab' => 'employees'])
            ->with('success', 'Employee and associated user restored successfully');
    }

    public function forceDeleteEmployee($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);

        \DB::transaction(function () use ($employee) {
            // Force delete related user if it exists and is soft-deleted
            if ($employee->user_id) {
                $user = User::onlyTrashed()->find($employee->user_id);
                if ($user) {
                    $user->forceDelete();
                }
            }

            $employee->forceDelete();
        });

        return redirect()->route('softdelete.index', ['tab' => 'employees'])
            ->with('success', 'Employee and associated user permanently deleted');
    }



    public function restoreDepartment($id)
    {
        $department = Department::onlyTrashed()->findOrFail($id);
        $department->restore();

        return redirect()->route('softdelete.index', ['tab' => 'departments'])
            ->with('success', 'Department restored successfully');
    }

    public function forceDeleteDepartment($id)
    {
        $department = Department::onlyTrashed()->findOrFail($id);
        $department->forceDelete();

        return redirect()->route('softdelete.index', ['tab' => 'departments'])
            ->with('success', 'Department permanently deleted');
    }

    public function restoreService($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();

        return redirect()->route('softdelete.index', ['tab' => 'services'])
            ->with('success', 'Service restored successfully');
    }

    public function forceDeleteService($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->forceDelete();

        return redirect()->route('softdelete.index', ['tab' => 'services'])
            ->with('success', 'Service permanently deleted');
    }



    public function restorePlan($id)
    {
        $plan = Plan::onlyTrashed()->findOrFail($id);
        $plan->restore();

        return redirect()->route('softdelete.index', ['tab' => 'plans'])
            ->with('success', 'Plan restored successfully');
    }

    public function forceDeletePlan($id)
    {
        $plan = Plan::onlyTrashed()->findOrFail($id);
        $plan->forceDelete();

        return redirect()->route('softdelete.index', ['tab' => 'plans'])
            ->with('success', 'Plan permanently deleted');
    }


    public function restoreCompany($id)
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        $company->restore();

        return redirect()->route('softdelete.index', ['tab' => 'companies'])
            ->with('success', 'Company restored successfully');
    }

    public function forceDeleteCompany($id)
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        $company->forceDelete();

        return redirect()->route('softdelete.index', ['tab' => 'companies'])
            ->with('success', 'Company permanently deleted');
    }








    public function restoreVehicle($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $vehicle->restore();

        return redirect()->route('softdelete.index', ['tab' => 'vehicles'])
            ->with('success', 'Vehicle restored successfully');
    }

    public function forceDeleteVehicle($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $vehicle->forceDelete();

        return redirect()->route('softdelete.index', ['tab' => 'vehicles'])
            ->with('success', 'Vehicle permanently deleted');
    }

    public function restoreCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('softdelete.index', ['tab' => 'customers'])
            ->with('success', 'Customer restored successfully');
    }

    public function forceDeleteCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->forceDelete();

        return redirect()->route('softdelete.index', ['tab' => 'customers'])
            ->with('success', 'Customer permanently deleted');
    }


}
