<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Payslip;
use App\Models\Payroll;
use App\Models\Notification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    /**
     * HR/Admin: Show all payrolls
     */
    public function index()
    {
        if (!auth()->user()->can('view employees') && !auth()->user()->can('view expenses')) {
            abort(403);
        }
        
        $payrolls = Payroll::with(['payslips.employee.user', 'processedBy'])
            ->latest()
            ->paginate(10);
        
        return view('payslips.index', compact('payrolls'));
    }

    /**
     * Employee: Show own payslips
     */
    public function myPayslips()
    {
        $employee = Employee::where('user_id', auth()->id())->first();
        
        if (!$employee) {
            return redirect()->route('dashboard')->with('error', 'Employee record not found.');
        }
        
        $payslips = Payslip::where('employee_id', $employee->id)
            ->with('payroll')
            ->latest()
            ->paginate(10);
        
        return view('payslips.my', compact('payslips', 'employee'));
    }

    /**
     * Show single payslip details
     */
    public function show(Payslip $payslip)
    {
        $employee = Employee::where('user_id', auth()->id())->first();
        
        // Allow viewing own payslip, or if HR/Admin/Accountant
        $isAuthorized = auth()->user()->can('view employees') ||
                        auth()->user()->can('view expenses') ||
                       ($employee && $payslip->employee_id !== $employee->id);
        if (!$isAuthorized) {
            abort(403, 'You can only view your own payslip.');
        }
        
        $payslip->load(['employee.user', 'employee.department', 'employee.position', 'payroll']);
        
        return view('payslips.show', compact('payslip'));
    }

    /**
     * HR/Admin: Generate payroll for all employees
     */
    public function generate(Request $request)
    {
        if (!auth()->user()->can('view employees') && !auth()->user()->can('view expenses')) {
            abort(403);
        }
        
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after:period_start',
            'payment_date' => 'required|date',
        ]);
        
        $payroll = Payroll::create([
            'payroll_number' => 'PAY-' . date('Ym') . '-' . str_pad(Payroll::count() + 1, 3, '0', STR_PAD_LEFT),
            'period_start' => $request->period_start,
            'period_end' => $request->period_end,
            'payment_date' => $request->payment_date,
            'status' => 'draft',
            'processed_by' => auth()->id(),
        ]);
        
        $employees = Employee::where('employment_status', 'active')->get();
        
        if ($employees->isEmpty()) {
            return back()->with('error', 'No active employees found.');
        }
        
        $totalAmount = 0;
        
        foreach ($employees as $employee) {
            $basicSalary = $employee->salary;
            $allowances = round($basicSalary * 0.10, 2);
            $deductions = round($basicSalary * 0.05, 2);
            $tax = round($basicSalary * 0.15, 2);
            $netSalary = $basicSalary + $allowances - $deductions - $tax;
            
            Payslip::create([
                'payroll_id' => $payroll->id,
                'employee_id' => $employee->id,
                'basic_salary' => $basicSalary,
                'allowances' => $allowances,
                'overtime' => 0,
                'bonus' => 0,
                'deductions' => $deductions,
                'tax' => $tax,
                'net_salary' => $netSalary,
            ]);
            
            $totalAmount += $netSalary;
        }
        
        $payroll->update([
            'total_amount' => $totalAmount,
            'status' => 'processed',
        ]);
        
        return redirect()->route('payslips.index')
            ->with('success', 'Payroll generated for ' . $employees->count() . ' employees. Total: $' . number_format($totalAmount, 2));
    }

    /**
     * HR/Admin: Mark payroll as paid
     */
    public function markPaid(Payroll $payroll)
    {
        if (!auth()->user()->can('view employees') && !auth()->user()->can('view expenses')) {
            abort(403);
        }
        
        $payroll->update(['status' => 'paid']);
        
        // Notify all employees
        foreach ($payroll->payslips as $payslip) {
            Notification::create([
                'user_id' => $payslip->employee->user_id,
                'title' => 'Salary Paid! 💰',
                'message' => 'Your salary for ' . $payroll->period_start->format('F Y') . ' has been paid. Net: $' . number_format($payslip->net_salary, 2),
                'type' => 'success',
                'icon' => 'bi bi-cash-stack',
            ]);
        }
        
        return back()->with('success', 'Payroll marked as paid and all employees notified!');
    }

    /**
     * Download payslip as PDF
     */
    public function download(Payslip $payslip)
    {
        $employee = Employee::where('user_id', auth()->id())->first();
        
        if (!auth()->user()->can('view employees') && 
            !auth()->user()->can('view expenses') && 
            $payslip->employee_id !== optional($employee)->id) {
            abort(403);
        }
        
        $payslip->load(['employee.user', 'employee.department', 'employee.position', 'payroll']);
        $companySettings = \App\Models\CompanySetting::first();
        
        $pdf = PDF::loadView('payslips.pdf', compact('payslip', 'companySettings'));
        
        return $pdf->download('Payslip_' . $payslip->employee->employee_code . '_' . $payslip->payroll->period_start->format('M_Y') . '.pdf');
    }

    /**
     * Print payslip
     */
    public function print(Payslip $payslip)
    {
        $employee = Employee::where('user_id', auth()->id())->first();
        
        if (!auth()->user()->can('view employees') && 
            !auth()->user()->can('view expenses') && 
            $payslip->employee_id !== optional($employee)->id) {
            abort(403);
        }
        
        $payslip->load(['employee.user', 'employee.department', 'employee.position', 'payroll']);
        
        return view('payslips.print', compact('payslip'));
    }
}