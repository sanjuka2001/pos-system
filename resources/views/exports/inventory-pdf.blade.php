<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        h1 { text-align: center; color: #4f46e5; margin-bottom: 5px; }
        .meta { text-align: center; color: #888; margin-bottom: 20px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #4f46e5; color: #fff; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; }
        td { padding: 6px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) { background: #f9fafb; }
        .low { color: #d97706; font-weight: bold; }
        .out { color: #dc2626; font-weight: bold; }
        .ok { color: #059669; }
    </style>
</head>
<body>
    <h1>Inventory Report</h1>
    <p class="meta">Generated: {{ $generatedAt }}</p>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Barcode</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Alert</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                @php
                    $stock = $product->inventory->quantity_in_stock ?? $product->stock;
                    $alert = $product->inventory->low_stock_alert ?? 10;
                @endphp
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->sku ?? '-' }}</td>
                    <td>{{ $product->barcode }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>LKR {{ number_format($product->price, 2) }}</td>
                    <td>{{ $stock }}</td>
                    <td>{{ $alert }}</td>
                    <td class="{{ $stock <= 0 ? 'out' : ($stock <= $alert ? 'low' : 'ok') }}">
                        {{ $stock <= 0 ? 'Out of Stock' : ($stock <= $alert ? 'Low Stock' : 'In Stock') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
