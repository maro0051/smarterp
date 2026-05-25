<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['invoice', 'customer', 'receivedBy'])
            ->latest()
            ->paginate(10);
        
        return view('payments.index', compact('payments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'customer_id' => 'required|exists:customers,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $validated['received_by'] = auth()->user()->id;

        $payment = Payment::create($validated);

        // Update invoice paid amount and status
        $invoice = Invoice::find($validated['invoice_id']);
        $newPaidAmount = $invoice->paid_amount + $validated['amount'];
        
        $status = $invoice->status;
        if ($newPaidAmount >= $invoice->total_amount) {
            $status = 'paid';
        } elseif ($newPaidAmount > 0) {
            $status = 'sent';
        }

        $invoice->update([
            'paid_amount' => $newPaidAmount,
            'status' => $status,
        ]);

        return redirect()->back()
            ->with('success', 'Payment recorded successfully.');
    }
}