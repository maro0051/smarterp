<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\HR\RecruitmentController;
use App\Http\Controllers\HR\InterviewController;
use App\Http\Controllers\HR\PerformanceReviewController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IDCardController;
use App\Http\Controllers\PayslipController;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home - redirect to login
Route::get('/', function () {
    return redirect()->route('login');
});

// All authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    // ==================== DASHBOARD ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== PROFILE ====================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ==================== EMPLOYEE MANAGEMENT ====================
    Route::resource('employees', EmployeeController::class)
        ->middleware('permission:view employees');

    // ==================== DEPARTMENTS ====================
    Route::resource('departments', DepartmentController::class)
        ->middleware('permission:view departments');

    // ==================== POSITIONS ====================
    Route::resource('positions', PositionController::class)
        ->middleware('permission:view positions');

    // ==================== ATTENDANCE ====================
    Route::get('attendance/punch', [AttendanceController::class, 'punch'])
        ->name('attendance.punch');
    
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->name('attendance.check-in');
    
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])
        ->name('attendance.check-out');
    
    Route::resource('attendance', AttendanceController::class)
        ->middleware('permission:view all attendance|view own attendance');

    // ==================== LEAVE REQUESTS ====================
    Route::post('leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])
        ->middleware('permission:approve leave requests')
        ->name('leave-requests.approve');
    
    Route::post('leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])
        ->middleware('permission:reject leave requests')
        ->name('leave-requests.reject');
    
    Route::resource('leave-requests', LeaveRequestController::class)
        ->middleware('permission:view all leave requests|view own leave requests');

    // ==================== HR MODULE ====================
    Route::prefix('hr')->name('hr.')->middleware('permission:view employees')->group(function () {
        // Recruitment
        Route::resource('recruitments', RecruitmentController::class);
        
        // Interviews
        Route::post('interviews', [InterviewController::class, 'store'])->name('interviews.store');
        Route::put('interviews/{interview}', [InterviewController::class, 'update'])->name('interviews.update');
        
        // Performance Reviews
        Route::resource('performance', PerformanceReviewController::class);
    });

      // ==================== ID CARDS (Admin & HR Only) ====================
    Route::prefix('id-cards')->name('id-cards.')->middleware('permission:view employees')->group(function () {
        Route::get('/', [IDCardController::class, 'index'])->name('index');
        Route::get('/generate/{employee}', [IDCardController::class, 'generate'])->name('generate');
        Route::get('/download/{employee}', [IDCardController::class, 'download'])->name('download');
        Route::get('/print/{employee}', [IDCardController::class, 'print'])->name('print');
        Route::get('/generate-all', [IDCardController::class, 'generateAll'])->name('generate-all');
        Route::get('/download-all', [IDCardController::class, 'downloadAll'])->name('download-all');
});

    // ==================== INVENTORY MANAGEMENT ====================
    // Stock Movements
    Route::get('stock-movements', [ProductController::class, 'stockMovements'])
        ->middleware('permission:view stock movements')
        ->name('stock-movements.index');

    // Products
    Route::resource('products', ProductController::class)
        ->middleware('permission:view products');

    // Categories
    Route::resource('categories', CategoryController::class)
        ->middleware('permission:view categories');

    // Suppliers
    Route::resource('suppliers', SupplierController::class)
        ->middleware('permission:view suppliers');

    // Warehouses
    Route::resource('warehouses', WarehouseController::class)
        ->middleware('permission:view warehouses');

    // ==================== SALES MANAGEMENT ====================
    // Customers
    Route::resource('customers', CustomerController::class)
        ->middleware('permission:view customers');

    // Invoices
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])
        ->middleware('permission:edit invoices')
        ->name('invoices.send');
    
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])
        ->name('invoices.pdf');
    
    Route::resource('invoices', InvoiceController::class)
        ->middleware('permission:view invoices');

    // Payments
    Route::resource('payments', PaymentController::class)
        ->middleware('permission:view payments');

    // ==================== ACCOUNTING ====================
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])
        ->middleware('permission:approve expenses')
        ->name('expenses.approve');
    
    Route::post('expenses/{expense}/reject', [ExpenseController::class, 'reject'])
        ->middleware('permission:reject expenses')
        ->name('expenses.reject');
    
    Route::resource('expenses', ExpenseController::class)
        ->middleware('permission:view expenses');

      // ==================== PAYSLIPS ====================
     // Employee self-service - no special permission needed, just auth
    Route::middleware('auth')->group(function () {
        Route::get('my-payslips', [PayslipController::class, 'myPayslips'])->name('payslips.my');
        Route::get('payslips/{payslip}', [PayslipController::class, 'show'])->name('payslips.show');
        Route::get('payslips/{payslip}/download', [PayslipController::class, 'download'])->name('payslips.download');
        Route::get('payslips/{payslip}/print', [PayslipController::class, 'print'])->name('payslips.print');
  }); 

      // HR/Admin management
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('payslips', [PayslipController::class, 'index'])
            ->middleware('permission:view employees|view expenses')
            ->name('payslips.index');
    
        Route::get('payslips/{payslip}', [PayslipController::class, 'show'])
            ->middleware('permission:view employees|view expenses')
            ->name('payslips.show');
    
        Route::post('payslips/generate', [PayslipController::class, 'generate'])
            ->middleware('permission:view employees|view expenses')
            ->name('payslips.generate');
    
        Route::post('payslips/{payroll}/mark-paid', [PayslipController::class, 'markPaid'])
            ->middleware('permission:view employees|view expenses')
            ->name('payslips.mark-paid');
  });
    // ==================== CRM ====================
    Route::post('leads/{lead}/convert', [LeadController::class, 'convertToCustomer'])
        ->middleware('permission:create customers')
        ->name('leads.convert');
    
    Route::resource('leads', LeadController::class)
        ->middleware('permission:view leads');

    // ==================== PROJECT MANAGEMENT ====================
    Route::post('tasks/{task}/update-status', [TaskController::class, 'updateStatus'])
        ->name('tasks.update-status');
    
    Route::post('tasks/update-order', [TaskController::class, 'updateOrder'])
        ->name('tasks.update-order');
    
    Route::resource('projects', ProjectController::class)
        ->middleware('permission:view projects');
    
    Route::resource('tasks', TaskController::class)
        ->middleware('permission:view tasks');

    // ==================== REPORTS ====================
    Route::prefix('reports')->name('reports.')->middleware('permission:view reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])
            ->middleware('permission:view sales reports')
            ->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventory'])
            ->middleware('permission:view inventory reports')
            ->name('inventory');
        Route::get('/financial', [ReportController::class, 'financial'])
            ->middleware('permission:view financial reports')
            ->name('financial');
        Route::get('/export/{type}', [ReportController::class, 'export'])
            ->middleware('permission:export reports')
            ->name('export');
    });

    // ==================== SETTINGS ====================
    Route::get('settings', [SettingController::class, 'index'])
        ->middleware('permission:view settings')
        ->name('settings.index');
    
    Route::put('settings', [SettingController::class, 'update'])
        ->middleware('permission:edit settings')
        ->name('settings.update');

    // ==================== USER MANAGEMENT (Super Admin) ====================
    Route::resource('users', UserController::class)
        ->middleware('permission:manage users');

    // ==================== ACTIVITY LOGS ====================
    Route::get('activity-logs', [ActivityLogController::class, 'index'])
        ->middleware('permission:view activity logs')
        ->name('activity-logs.index');

    /// Employee Task Routes (outside verified middleware)
    Route::middleware('auth')->group(function () {
        Route::get('my-tasks', [TaskController::class, 'myTasks'])->name('tasks.my');
        Route::get('tasks/{task}/view', [TaskController::class, 'view'])->name('tasks.view');
    });

});

// Include authentication routes
require __DIR__.'/auth.php';