<?php
// app/Http/Controllers/ReportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Customer;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth());

        $invoices = Invoice::whereBetween('invoice_date', [$startDate, $endDate])
            ->with('customer')
            ->get();

        $totalSales = $invoices->sum('total_amount');
        $totalPaid = $invoices->sum('paid_amount');
        $totalDue = $totalSales - $totalPaid;
        $invoiceCount = $invoices->count();

        // Daily sales for chart
        $dailySales = [];
        $currentDate = Carbon::parse($startDate);
        while ($currentDate <= $endDate) {
            $dailySales[$currentDate->format('Y-m-d')] = $invoices
                ->where('invoice_date', $currentDate->format('Y-m-d'))
                ->sum('total_amount');
            $currentDate->addDay();
        }

        return view('reports.sales', compact(
            'invoices', 'totalSales', 'totalPaid', 'totalDue', 
            'invoiceCount', 'dailySales', 'startDate', 'endDate'
        ));
    }

    public function inventory()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->get();

        $totalProducts = $products->count();
        $totalValue = $products->sum(function($product) {
            return $product->cost_price * 100; // Assuming quantity 100 for demo
        });

        $categories = $products->groupBy('category.name');
        
        return view('reports.inventory', compact('products', 'totalProducts', 'totalValue', 'categories'));
    }

    public function financial(Request $request)
    {
        $year = $request->get('year', Carbon::now()->year);

        $monthlyRevenue = [];
        $monthlyExpenses = [];
        $monthlyProfit = [];

        for ($month = 1; $month <= 12; $month++) {
            $revenue = Invoice::whereYear('invoice_date', $year)
                ->whereMonth('invoice_date', $month)
                ->sum('total_amount');

            $expenses = Expense::whereYear('expense_date', $year)
                ->whereMonth('expense_date', $month)
                ->where('status', 'approved')
                ->sum('amount');

            $monthlyRevenue[$month] = $revenue;
            $monthlyExpenses[$month] = $expenses;
            $monthlyProfit[$month] = $revenue - $expenses;
        }

        $totalRevenue = array_sum($monthlyRevenue);
        $totalExpenses = array_sum($monthlyExpenses);
        $totalProfit = $totalRevenue - $totalExpenses;

        return view('reports.financial', compact(
            'year', 'monthlyRevenue', 'monthlyExpenses', 
            'monthlyProfit', 'totalRevenue', 'totalExpenses', 'totalProfit'
        ));
    }

    public function export($type)
    {
        // Export functionality
        switch ($type) {
            case 'sales-pdf':
                $invoices = Invoice::with('customer')->get();
                $pdf = PDF::loadView('reports.exports.sales-pdf', compact('invoices'));
                return $pdf->download('sales-report.pdf');
                break;
            
            case 'inventory-pdf':
                $products = Product::with('category')->get();
                $pdf = PDF::loadView('reports.exports.inventory-pdf', compact('products'));
                return $pdf->download('inventory-report.pdf');
                break;
            
            default:
                return redirect()->back()->with('error', 'Invalid export type');
        }
    }
}