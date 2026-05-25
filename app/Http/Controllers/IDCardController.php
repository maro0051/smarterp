<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class IDCardController extends Controller
{
    /**
     * Show list of employees with ID card options
     */
    public function index()
    {
        $employees = Employee::with(['user', 'department', 'position'])
            ->where('employment_status', 'active')
            ->latest()
            ->paginate(12);
        
        return view('id-cards.index', compact('employees'));
    }

    /**
     * Generate single ID card
     */
    public function generate(Employee $employee)
    {
        $employee->load(['user', 'department', 'position']);
        
        return view('id-cards.single', compact('employee'));
    }

    /**
     * Download ID card as PDF
     */
    public function download(Employee $employee)
    {
        $employee->load(['user', 'department', 'position']);
        
        $companySettings = \App\Models\CompanySetting::first();
        
        $pdf = PDF::loadView('id-cards.pdf', compact('employee', 'companySettings'));
        $pdf->setPaper([0, 0, 204, 324], 'portrait'); // Credit card size in mm
        
        return $pdf->download('ID_Card_' . $employee->employee_code . '.pdf');
    }

    /**
     * Print ID card
     */
    public function print(Employee $employee)
    {
        $employee->load(['user', 'department', 'position']);
        
        return view('id-cards.print', compact('employee'));
    }

    /**
     * Generate all ID cards
     */
    public function generateAll()
    {
        $employees = Employee::with(['user', 'department', 'position'])
            ->where('employment_status', 'active')
            ->get();
        
        return view('id-cards.all', compact('employees'));
    }

    /**
     * Download all ID cards as PDF
     */
    public function downloadAll()
    {
        $employees = Employee::with(['user', 'department', 'position'])
            ->where('employment_status', 'active')
            ->get();
        
        $companySettings = \App\Models\CompanySetting::first();
        
        $pdf = PDF::loadView('id-cards.pdf-all', compact('employees', 'companySettings'));
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('All_Employee_ID_Cards.pdf');
    }
}