<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Employee;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Lead;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payslip;
use App\Models\Task;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role
     */
    public function index()
    {
        $user = auth()->user();
        
        // Load ALL possible data for the unified dashboard
        $data = $this->loadAllData();
        
        // Determine which view to show
        $isEmployee = !$user->can('view employees') && !$user->can('view invoices') && !$user->can('view products');
        $isHR = $user->can('view employees') && !$user->can('view invoices') && !$user->can('view products');
        
        // All roles use the same dashboard view with different data sections
        return view('dashboard', $data);
    }

    /**
     * Load all dashboard data
     */
    private function loadAllData()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->first();
        
        // Admin data
        $totalEmployees = Employee::where('employment_status', 'active')->count();
        $totalCustomers = Customer::count();
        $totalProducts = Product::count();
        $totalRevenue = Invoice::where('status', 'paid')->sum('total_amount');
        $totalExpenses = Expense::where('status', 'approved')->sum('amount');
        $totalProjects = Project::whereIn('status', ['in_progress', 'planning'])->count();
        $totalLeads = Lead::whereIn('status', ['new', 'contacted', 'qualified'])->count();
        
        // Monthly data
        $monthlyRevenue = [];
        $monthlyExpenses = [];
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $monthlyRevenue[] = (float)Invoice::where('status', 'paid')->whereYear('invoice_date', $date->year)->whereMonth('invoice_date', $date->month)->sum('total_amount');
            $monthlyExpenses[] = (float)Expense::where('status', 'approved')->whereYear('expense_date', $date->year)->whereMonth('expense_date', $date->month)->sum('amount');
        }

        $recentInvoices = Invoice::with('customer')->latest()->take(5)->get();
        $recentExpenses = Expense::with(['category', 'createdBy'])->latest()->take(5)->get();
        $lowStockProducts = Product::where('is_active', true)->orderBy('reorder_level')->take(5)->get();
        
        $employeesByDepartment = DB::table('employees')->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('count(*) as total'))
            ->where('employees.employment_status', 'active')->groupBy('departments.name')->get();
        
        $recentActivities = \App\Models\ActivityLog::with('user')->latest()->take(10)->get();

        // HR data
        $totalDepartments = Department::count();
        $onLeaveToday = Employee::where('employment_status', 'on_leave')->count();
        $newHiresThisMonth = Employee::whereMonth('joining_date', now()->month)->whereYear('joining_date', now()->year)->count();
        $presentToday = Attendance::whereDate('date', Carbon::today())->whereIn('status', ['present', 'late'])->count();
        $absentToday = max(0, $totalEmployees - $presentToday);
        $pendingLeaves = LeaveRequest::with(['employee.user', 'leaveType'])->where('status', 'pending')->latest()->take(5)->get();
        $pendingLeaveCount = LeaveRequest::where('status', 'pending')->count();
        $recentHires = Employee::with(['user', 'department', 'position'])->latest('joining_date')->take(5)->get();
        $openRecruitments = \App\Models\Recruitment::where('status', 'open')->count();
        $deptDistribution = DB::table('employees')->join('departments', 'employees.department_id', '=', 'departments.id')
            ->select('departments.name', DB::raw('count(*) as total'))
            ->where('employees.employment_status', 'active')->groupBy('departments.name')->get();
        $upcomingInterviews = \App\Models\Interview::with(['recruitment', 'interviewer'])->where('status', 'scheduled')->where('interview_date', '>=', now())->orderBy('interview_date')->take(5)->get();
        
        // Attendance trend
        $attendanceTrend = [];
        $trendMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $trendMonths[] = $date->format('M');
            $attendanceTrend[] = Attendance::whereMonth('date', $date->month)->whereYear('date', $date->year)->whereIn('status', ['present', 'late'])->count();
        }

        // Employee data
        $todayAttendance = null;
        $monthStats = ['present' => 0, 'late' => 0, 'absent' => 0];
        $latestPayslip = null;
        $pendingTasks = collect();
        $leaveBalance = [];
        
        if ($employee) {
            $todayAttendance = Attendance::where('employee_id', $employee->id)->whereDate('date', Carbon::today())->first();
            $monthStart = Carbon::now()->startOfMonth();
            $monthEnd = Carbon::now()->endOfMonth();
            $monthStats = [
                'present' => Attendance::where('employee_id', $employee->id)->whereBetween('date', [$monthStart, $monthEnd])->where('status', 'present')->count(),
                'late' => Attendance::where('employee_id', $employee->id)->whereBetween('date', [$monthStart, $monthEnd])->where('status', 'late')->count(),
                'absent' => Attendance::where('employee_id', $employee->id)->whereBetween('date', [$monthStart, $monthEnd])->where('status', 'absent')->count(),
            ];
            $latestPayslip = Payslip::where('employee_id', $employee->id)->with('payroll')->latest()->first();
            $pendingTasks = Task::where('assigned_to', $user->id)->where('status', '!=', 'completed')->with('project')->latest()->take(5)->get();
            
            $leaveTypes = \App\Models\LeaveType::where('is_active', true)->get();
            foreach ($leaveTypes as $type) {
                $used = LeaveRequest::where('employee_id', $employee->id)->where('leave_type_id', $type->id)->where('status', 'approved')->whereYear('start_date', date('Y'))->sum('total_days');
                $leaveBalance[] = ['type' => $type->name, 'total' => $type->days_per_year, 'used' => $used, 'remaining' => $type->days_per_year - $used];
            }
        }

        // Accountant data
        $pendingExpenses = Expense::where('status', 'pending')->count();
        $pendingExpenseAmount = Expense::where('status', 'pending')->sum('amount');
        $pendingExpensesList = Expense::with(['category', 'createdBy'])->where('status', 'pending')->latest()->take(5)->get();
        $overdueInvoices = Invoice::where('status', 'overdue')->count();
        $overdueAmount = Invoice::where('status', 'overdue')->sum('total_amount');
        
        $monthlyProfit = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $rev = Invoice::where('status', 'paid')->whereYear('invoice_date', $date->year)->whereMonth('invoice_date', $date->month)->sum('total_amount');
            $exp = Expense::where('status', 'approved')->whereYear('expense_date', $date->year)->whereMonth('expense_date', $date->month)->sum('amount');
            $monthlyProfit[] = (float)($rev - $exp);
        }

        return compact(
            'totalEmployees', 'totalCustomers', 'totalProducts', 'totalRevenue', 'totalExpenses', 'totalProjects', 'totalLeads',
            'monthlyRevenue', 'monthlyExpenses', 'months',
            'recentInvoices', 'recentExpenses', 'lowStockProducts', 'employeesByDepartment', 'recentActivities',
            'totalDepartments', 'onLeaveToday', 'newHiresThisMonth', 'presentToday', 'absentToday',
            'pendingLeaves', 'pendingLeaveCount', 'recentHires', 'openRecruitments', 'deptDistribution', 'upcomingInterviews',
            'attendanceTrend', 'trendMonths',
            'employee', 'todayAttendance', 'monthStats', 'latestPayslip', 'pendingTasks', 'leaveBalance',
            'pendingExpenses', 'pendingExpenseAmount', 'pendingExpensesList', 'overdueInvoices', 'overdueAmount', 'monthlyProfit'
        );
    }

    public function apiStats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_employees' => Employee::where('employment_status', 'active')->count(),
                'total_customers' => Customer::count(),
                'total_products' => Product::count(),
                'total_revenue' => Invoice::where('status', 'paid')->sum('total_amount'),
                'total_expenses' => Expense::where('status', 'approved')->sum('amount'),
                'total_projects' => Project::whereIn('status', ['in_progress', 'planning'])->count(),
                'total_leads' => Lead::whereIn('status', ['new', 'contacted', 'qualified'])->count(),
            ]
        ]);
    }
}