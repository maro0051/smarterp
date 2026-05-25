{{-- resources/views/invoices/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Create Invoice</h2>
        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <!-- Invoice Items -->
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
                                        <th style="width: 100px;">Quantity</th>
                                        <th style="width: 150px;">Unit Price</th>
                                        <th style="width: 150px;">Total</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[0][product_id]" class="form-select product-select" required 
                                                    onchange="updatePrice(this)">
                                                <option value="">Select Product</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}" 
                                                            data-price="{{ $product->selling_price }}"
                                                            data-name="{{ $product->name }}">
                                                        {{ $product->name }} ({{ $product->sku }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][quantity]" class="form-control quantity" 
                                                   value="1" min="1" required onchange="calculateTotal(this)">
                                        </td>
                                        <td>
                                            <input type="number" name="items[0][unit_price]" class="form-control unit-price" 
                                                   value="0" step="0.01" min="0" required onchange="calculateTotal(this)">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Invoice Details -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="mb-0">Invoice Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" value="{{ $invoiceNumber }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">Customer *</label>
                            <select name="customer_id" id="customer_id" class="form-select" required>
                                <option value="">Select Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name }} - {{ $customer->company_name ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="invoice_date" class="form-label">Invoice Date *</label>
                            <input type="date" name="invoice_date" id="invoice_date" class="form-control" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date *</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" 
                                   value="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="tax_rate" class="form-label">Tax Rate (%)</label>
                            <input type="number" name="tax_rate" id="tax_rate" class="form-control" 
                                   value="0" min="0" max="100" onchange="calculateTotals()">
                        </div>
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount ($)</label>
                            <input type="number" name="discount" id="discount" class="form-control" 
                                   value="0" min="0" step="0.01" onchange="calculateTotals()">
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:</span>
                            <span id="discountDisplay">-$0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong id="total" class="text-primary">$0.00</strong>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save me-2"></i>Create Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let itemIndex = 1;

    function addItem() {
        const tbody = document.getElementById('itemsBody');
        const newRow = `
            <tr class="item-row">
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-select product-select" required onchange="updatePrice(this)">
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" 
                                    data-price="{{ $product->selling_price }}"
                                    data-name="{{ $product->name }}">
                                {{ $product->name }} ({{ $product->sku }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" 
                           value="1" min="1" required onchange="calculateTotal(this)">
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" class="form-control unit-price" 
                           value="0" step="0.01" min="0" required onchange="calculateTotal(this)">
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
        } else {
            showToast('At least one item is required', 'warning');
        }
    }

    function updatePrice(select) {
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.dataset.price;
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