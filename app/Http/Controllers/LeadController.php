<?php
// app/Http/Controllers/LeadController.php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Customer;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::with(['assignedTo', 'createdBy'])
            ->latest()
            ->paginate(10);
        
        $totalLeads = Lead::count();
        $wonLeads = Lead::where('status', 'won')->count();
        $totalValue = Lead::sum('potential_value');
        
        return view('leads.index', compact('leads', 'totalLeads', 'wonLeads', 'totalValue'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        return view('leads.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company_name' => 'nullable|string',
            'source' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'potential_value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
        ]);

        $validated['created_by'] = auth()->id();

        Lead::create($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead)
    {
        $lead->load(['assignedTo', 'createdBy']);
        return view('leads.show', compact('lead'));
    }

    public function edit(Lead $lead)
    {
        $users = \App\Models\User::all();
        return view('leads.edit', compact('lead', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company_name' => 'nullable|string',
            'source' => 'nullable|string',
            'status' => 'required|in:new,contacted,qualified,proposal,negotiation,won,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'potential_value' => 'nullable|numeric|min:0',
            'expected_close_date' => 'nullable|date',
        ]);

        $lead->update($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function convertToCustomer(Lead $lead)
    {
        // Create customer from lead
        $customer = Customer::create([
            'name' => $lead->name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'company_name' => $lead->company_name,
            'code' => 'CUST-' . str_pad(Customer::count() + 1, 4, '0', STR_PAD_LEFT),
        ]);

        // Update lead status
        $lead->update(['status' => 'won']);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Lead converted to customer successfully.');
    }
}