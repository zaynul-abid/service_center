<?php

use App\Http\Controllers\Auth\AuthanticationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Followup\ServiceFollowUpController;
use App\Http\Controllers\Founder\FounderCompanyCreationController;
use App\Http\Controllers\Founder\FounderSuperadminController;
use App\Http\Controllers\Frontend\FrontendEmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Service\CustomerController;
use App\Http\Controllers\Service\EmployeeAssignController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Service\VehicleController;
use App\Http\Controllers\Softdelete\SoftDeleteController;
use App\Http\Controllers\Superadmin\AdminCreationController;
use App\Http\Controllers\Superadmin\DepartmentController;
use App\Http\Controllers\Superadmin\EmployeeController;
use App\Http\Controllers\Company\CompanyRenewalController;
use \App\Http\Controllers\Company\PlanController;
use Illuminate\Support\Facades\Route;



Route::get('/',[AuthanticationController::class,'login'])->name('login');
Route::post('/logout', [AuthanticationController::class, 'logout'])->name('logout');



// Founder Routes (Only Founder)
Route::middleware(['auth', 'usertype:founder'])->group(function () {

    Route::get('/founder/dashboard',[DashboardController::class,'founderIndex'])->name('founder.dashboard');

    Route::post('/companies/{id}/update-status', [CompanyRenewalController::class, 'updateStatus'])
        ->name('companies.update-status');

    Route::get('/companies/{company}/renew', [CompanyRenewalController::class, 'showRenewalForm'])->name('companies.renew');
    Route::post('/companies/{company}/renew', [CompanyRenewalController::class, 'processRenewal'])->name('companies.process-renewal');

    Route::resource('plans', PlanController::class);
    Route::get('/dashboard/subscriptions', [PlanController::class, 'showSubscriptions'])->name('plan_history.index');


    Route::prefix('founder')->group(function () {

        Route::resource('superadmins', FounderSuperadminController::class);

        Route::resource('companies', FounderCompanyCreationController::class);
    });


});

// Superadmin Routes (Founder and Superadmin)
Route::middleware(['auth', 'usertype:superadmin'])->group(function () {


    Route::get('/superadmin/dashboard',[DashboardController::class,'superadminIndex'])->name('superadmin.dashboard');

    Route::prefix('superadmin')->group(function () {

        Route::resource('superadmin-admins', AdminCreationController::class);

        Route::resource('departments', DepartmentController::class);

        Route::resource('employees', EmployeeController::class);

        Route::get('/employee/services/{id}', [EmployeeController::class, 'getServices'])->name('employee.services');




    });



    Route::prefix('softdelete')->name('softdelete.')->group(function () {
        // Index route (GET)
        Route::get('/', [SoftDeleteController::class, 'index'])->name('index');

        // Restore routes (POST)
        Route::get('/restore/user/{id}', [SoftDeleteController::class, 'restoreUser'])->name('restore.user');
        Route::get('/restore/employee/{id}', [SoftDeleteController::class, 'restoreEmployee'])->name('restore.employee');
        Route::get('/restore/department/{id}', [SoftDeleteController::class, 'restoreDepartment'])->name('restore.department');
        Route::get('/restore/service/{id}', [SoftDeleteController::class, 'restoreService'])->name('restore.service');
        Route::get('/restore/plan/{id}', [SoftDeleteController::class, 'restorePlan'])->name('restore.plan');
        Route::get('/restore/company/{id}', [SoftDeleteController::class, 'restoreCompany'])->name('restore.company');

        Route::get('/restore/customer/{id}', [SoftDeleteController::class, 'restoreCustomer'])->name('restore.customer');
        Route::get('/restore/vehicle/{id}', [SoftDeleteController::class, 'restoreVehicle'])->name('restore.vehicle');


        // Force delete routes (DELETE)
        Route::delete('/force-delete/user/{id}', [SoftDeleteController::class, 'forceDeleteUser'])->name('forceDelete.user');
        Route::delete('/force-delete/employee/{id}', [SoftDeleteController::class, 'forceDeleteEmployee'])->name('forceDelete.employee');
        Route::delete('/force-delete/department/{id}', [SoftDeleteController::class, 'forceDeleteDepartment'])->name('forceDelete.department');
        Route::delete('/force-delete/service/{id}', [SoftDeleteController::class, 'forceDeleteService'])->name('forceDelete.service');
        Route::delete('/force-delete/plan/{id}', [SoftDeleteController::class, 'forceDeletePlan'])->name('forceDelete.plan');
        Route::delete('/force-delete/company/{id}', [SoftDeleteController::class, 'forceDeleteCompany'])->name('forceDelete.company');


        Route::delete('/force-delete/vehicle/{id}', [SoftDeleteController::class, 'forceDeleteVehicle'])->name('forceDelete.vehicle');
        Route::delete('/force-delete/customer/{id}', [SoftDeleteController::class, 'forceDeleteCustomer'])->name('forceDelete.customer');

    });
});





// Admin Routes (Founder, Superadmin, and Admin)
Route::middleware(['auth', 'usertype:admin'])->group(function () {

    Route::get('/admin/dashboard',[DashboardController::class,'adminIndex'])->name('admin.dashboard');

    Route::get('/employee/show-assign', [EmployeeAssignController::class, 'showAssignPage'])->name('show.assign');
    Route::post('/employee/assign', [EmployeeAssignController::class, 'assign'])->name('store.assign');

    Route::get('/admin/service/reports', [ReportController::class, 'showServiceReport'])->name('admin.service.report');
    Route::get('/admin/employee/reports', [ReportController::class, 'showEmployeeReport'])->name('admin.employee.report');
    Route::get('/admin/reports/service/download', [ReportController::class, 'downloadServiceReport'])->name('report.service.download');

    Route::get('/admin/reports/employee/download', [ReportController::class, 'downloadEmployeeReport'])->name('report.employee.download');

    Route::get('/report/service-cost', [ReportController::class, 'showServiceCost'])->name('report.service.cost');
    Route::get('/report/service-cost/download', [ReportController::class, 'downloadServiceCostReport'])->name('cost.report.download');


    Route::get('/services/followup', [ServiceFollowUpController::class, 'followUp'])->name('service.followups');
});



// Admin Routes (Founder, Superadmin, Admin, Employee)
Route::middleware(['auth', 'usertype:employee'])->group(function () {

    Route::get('/employee/dashboard',[DashboardController::class,'employeeIndex'])->name('employee.dashboard');

    Route::resource('services', ServiceController::class);
    Route::get('/services/get-images/{id}', [ServiceController::class, 'getImages'])->name('services.getImages');

    Route::get('/vehicles/search', [VehicleController::class, 'search'])->name('vehicles.search');
    Route::get('/customers/search', [CustomerController::class, 'search']);
    Route::get('/vehicles/companies', [VehicleController::class, 'getCompanies']);
    Route::get('/vehicles/models', [VehicleController::class, 'getVehicleModels']);


    Route::get('/logged-employee/show-status', [FrontendEmployeeController::class, 'showStatus'])->name('logedEmployee.showStatus');
   Route::post('/employee/update-status/{id}', [FrontendEmployeeController::class, 'updateStatus'])->name('employee.updateStatus');
    Route::post('/employee/update-notes', [FrontendEmployeeController::class, 'updateNotes'])->name('employee.updateNotes');

});



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
