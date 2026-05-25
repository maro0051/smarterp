{{-- resources/views/reports/exports/inventory-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Inventory Report</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; padding: 20px; }
        h1 { color: #1e3a8a; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #1e3a8a; color: white; padding: 10px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <h1>Inventory Report</h1>
    <p>Generated on: {{ date('F d, Y') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->category->name }}</td>
                <td>${{ number_format($product->cost_price, 2) }}</td>
                <td>${{ number_format($product->selling_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>