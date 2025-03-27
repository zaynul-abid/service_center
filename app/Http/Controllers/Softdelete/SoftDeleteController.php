<?php

namespace App\Http\Controllers\Softdelete;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class SoftDeleteController extends Controller
{
//    public function index()
//    {
//        $deletedUsers = User::onlyTrashed()->paginate(10);
//        return view('softdelete.recover_deleted', compact('deletedUsers'));
//    }
//
//    public function restore($id)
//    {
//        // Restore the soft-deleted User
//        $user = User::onlyTrashed()->where('id', $id)->first();
//
//        if ($user) {
//            $user->restore(); // Restore the user
//
//            // Restore the associated employee, if any
//            $employee = Employee::onlyTrashed()->where('user_id', $user->id)->first();
//            if ($employee) {
//                $employee->restore();
//            }
//
//            return redirect()->back()->with('success', 'User and associated Employee restored successfully');
//        }
//
//        return redirect()->back()->with('error', 'User not found');
//    }
//
//    public function forceDelete($id)
//    {
//        User::onlyTrashed()->where('id', $id)->forceDelete();
//        return redirect()->back()->with('success', 'User permanently deleted');
//    }


    public function index()
    {
        $deletedUsers = User::onlyTrashed()->paginate(10, ['*'], 'users_page');
        $deletedEmployees = Employee::onlyTrashed()->paginate(10, ['*'], 'employees_page');
        $deletedCustomers = Customer::onlyTrashed()->paginate(10, ['*'], 'customers_page');
        $deletedDepartments = Department::onlyTrashed()->paginate(10, ['*'], 'departments_page');
        $deletedServices = Service::onlyTrashed()->paginate(10, ['*'], 'services_page');
        $deletedVehicles = Vehicle::onlyTrashed()->paginate(10, ['*'], 'vehicles_page');

        return view('softdelete.recover_deleted', compact(
            'deletedUsers',
            'deletedEmployees',
            'deletedCustomers',
            'deletedDepartments',
            'deletedServices',
            'deletedVehicles'
        ));
    }

    /**
     * Restore the specified soft-deleted user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        \DB::transaction(function () use ($user) {
            $user->restore();

            // Restore associated employee if exists
            if ($employee = Employee::onlyTrashed()->where('user_id', $user->id)->first()) {
                $employee->restore();
            }
        });

        return redirect()->route('softdelete.index')
            ->with('success', 'User and associated employee restored successfully');
    }

    /**
     * Permanently delete the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        \DB::transaction(function () use ($user) {
            // First delete associated employee if exists
            if ($employee = Employee::onlyTrashed()->where('user_id', $user->id)->first()) {
                $employee->forceDelete();
            }

            $user->forceDelete();
        });

        return redirect()->route('softdelete.index')
            ->with('success', 'User permanently deleted');
    }

    /**
     * Restore the specified soft-deleted employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreEmployee($id)
    {
        dd('test');
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->restore();

        return redirect()->route('softdelete.index')
            ->with('success', 'Employee restored successfully');
    }

    /**
     * Permanently delete the specified employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteEmployee($id)
    {
        $employee = Employee::onlyTrashed()->findOrFail($id);
        $employee->forceDelete();

        return redirect()->route('softdelete.index')
            ->with('success', 'Employee permanently deleted');
    }

    /**
     * Restore the specified soft-deleted customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('softdelete.index')
            ->with('success', 'Customer restored successfully');
    }

    /**
     * Permanently delete the specified customer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteCustomer($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->forceDelete();

        return redirect()->route('softdelete.index')
            ->with('success', 'Customer permanently deleted');
    }

    /**
     * Restore the specified soft-deleted department.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreDepartment($id)
    {
        $department = Department::onlyTrashed()->findOrFail($id);
        $department->restore();

        return redirect()->route('softdelete.index')
            ->with('success', 'Department restored successfully');
    }

    /**
     * Permanently delete the specified department.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteDepartment($id)
    {
        $department = Department::onlyTrashed()->findOrFail($id);
        $department->forceDelete();

        return redirect()->route('softdelete.index')
            ->with('success', 'Department permanently deleted');
    }

    /**
     * Restore the specified soft-deleted service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreService($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->restore();

        return redirect()->route('softdelete.index')
            ->with('success', 'Service restored successfully');
    }

    /**
     * Permanently delete the specified service.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteService($id)
    {
        $service = Service::onlyTrashed()->findOrFail($id);
        $service->forceDelete();

        return redirect()->route('softdelete.index')
            ->with('success', 'Service permanently deleted');
    }

    /**
     * Restore the specified soft-deleted vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreVehicle($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $vehicle->restore();

        return redirect()->route('softdelete.index')
            ->with('success', 'Vehicle restored successfully');
    }

    /**
     * Permanently delete the specified vehicle.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forceDeleteVehicle($id)
    {
        $vehicle = Vehicle::onlyTrashed()->findOrFail($id);
        $vehicle->forceDelete();

        return redirect()->route('softdelete.index')
            ->with('success', 'Vehicle permanently deleted');
    }
}
