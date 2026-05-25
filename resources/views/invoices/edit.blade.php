{{-- resources/views/invoices/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Invoice')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Edit Invoice: #{{ $invoice->invoice_number }}</h2>
    
    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
        @csrf @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Invoice Items</h5>
                        <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                            <i class="bi bi-plus-lg"></i> Add Item
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    @foreach($invoice->items as $index => $item)
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[{{ $index }}][product_id]" class="form-select product-select" required>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-price="{{ $product->selling_price }}"
                                                            {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" 
                                                   value="{{ $item->quantity }}" min="1" required onchange="calculateTotal(this)">
                                        </td>
                                        <td>
                                            <input type="number" name="items[{{ $index }}][unit_price]" class="form-control unit-price" 
                                                   value="{{ $item->unit_price }}" step="0.01" required onchange="calculateTotal(this)">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control item-total" readonly value="{{ number_format($item->total_price, 2) }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header"><h5 class="mb-0">Invoice Details</h5></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer *</label>
                            <select name="customer_id" id="customer_id" class="form-select" required>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date *</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" 
                                   value="{{ $invoice->invoice_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date *</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" 
                                   value="{{ $invoice->due_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" class="form-control" 
                                   value="{{ $invoice->tax_amount > 0 ? ($invoice->tax_amount / $invoice->subtotal) * 100 : 0 }}" 
                                   onchange="calculateTotals()">
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount ($)</label>
                            <input type="number" name="discount" id="discount" class="form-control" 
                                   value="{{ $invoice->discount_amount }}" step="0.01" onchange="calculateTotals()">
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ $invoice->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><h5 class="mb-0">Summary</h5></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">${{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span id="tax">${{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span id="discountDisplay">-${{ number_format($invoice->discount_amount, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong id="total" class="text-primary">${{ number_format($invoice->total_amount, 2) }}</strong>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i>Update Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let itemIndex = {{ $invoice->items->count() }};

    function addItem() {
        const tbody = document.getElementById('itemsBody');
        const newRow = `
            <tr class="item-row">
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-select product-select" required onchange="updatePrice(this)">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" value="1" min="1" required onchange="calculateTotal(this)">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" class="form-control unit-price" value="0" step="0.01" required onchange="calculateTotal(this)">
                </td>
                <td>
                    <input type="text" class="form-control item-total" readonly value="0.00">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(this)">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.insertAdjacentHTML('beforeend', newRow);
        itemIndex++;
    }

    function removeItem(button) {
        const tbody = document.getElementById('itemsBody');
        if (tbody.children.length > 1) {
            button.closest('tr').remove();
            calculateTotals();
        }
    }

    function updatePrice(select) {
        const price = select.options[select.selectedIndex].dataset.price;
        const row = select.closest('tr');
        if (price) {
            row.querySelector('.unit-price').value = price;
            calculateTotal(row.querySelector('.quantity'));
        }
    }

    function calculateTotal(element) {
        const row = element.closest('tr');
        const quantity = row.querySelector('.quantity').value || 0;
        const unitPrice = row.querySelector('.unit-price').value || 0;
        const total = quantity * unitPrice;
        row.querySelector('.item-total').value = total.toFixed(2);
        calculateTotals();
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const total = parseFloat(row.querySelector('.item-total').value) || 0;
            subtotal += total;
        });
        const taxRate = parseFloat(document.getElementById('tax_rate').value) || 0;
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const total = subtotal + taxAmount - discount;

        document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
        document.getElementById('tax').textContent = '$' + taxAmount.toFixed(2);
        document.getElementById('discountDisplay').textContent = '-$' + discount.toFixed(2);
        document.getElementById('total').textContent = '$' + total.toFixed(2);
    }
</script>
@endpush
@endsection