<?php
// app/Http/Controllers/ExpenseController.php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;        

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['category', 'createdBy', 'approvedBy'])
            ->latest()
            ->paginate(10);
        
        $totalExpenses = Expense::where('status', 'approved')->sum('amount');
        $pendingExpenses = Expense::where('status', 'pending')->sum('amount');
        
        return view('expenses.index', compact('expenses', 'totalExpenses', 'pendingExpenses'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $expenseNumber = 'EXP-' . date('Ymd') . '-' . str_pad(Expense::count() + 1, 4, '0', STR_PAD_LEFT);
        
        return view('expenses.create', compact('categories', 'expenseNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'vendor_name' => 'nullable|string',
            'description' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $validated['expense_number'] = 'EXP-' . date('Ymd') . '-' . str_pad(Expense::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['created_by'] = Auth::id();

        if ($request->hasFile('receipt_image')) {
            $validated['receipt_image'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['category', 'createdBy', 'approvedBy']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        if ($expense->status != 'pending') {
            return redirect()->route('expenses.show', $expense->id)
                ->with('error', 'Only pending expenses can be edited.');
        }

        $categories = Category::where('is_active', true)->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->status != 'pending') {
            return redirect()->route('expenses.show', $expense->id)
                ->with('error', 'Only pending expenses can be edited.');
        }

        $validated = $request->validate([
            'expense_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'vendor_name' => 'nullable|string',
            'description' => 'nullable|string',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        if ($request->hasFile('receipt_image')) {
            if ($expense->receipt_image) {
                Storage::disk('public')->delete($expense->receipt_image);
            }
            $validated['receipt_image'] = $request->file('receipt_image')->store('receipts', 'public');
        }

        $expense->update($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->receipt_image) {
            Storage::disk('public')->delete($expense->receipt_image);
        }
        
        $expense->delete();

        return redirect()->route('expenses.index')
            ->with('success', 'Expense deleted successfully.');
    }

    public function approve(Expense $expense)
    {
        $expense->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Expense approved successfully.');
    }

    public function reject(Expense $expense)
    {
        $expense->update(['status' => 'rejected']);

        return redirect()->back()
            ->with('success', 'Expense rejected.');
    }
}