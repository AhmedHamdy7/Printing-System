<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section table {
            width: 100%;
        }
        .info-section td {
            padding: 5px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .products-table th {
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .products-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        .totals {
            float: right;
            width: 300px;
        }
        .totals table {
            width: 100%;
        }
        .totals td {
            padding: 8px;
            border-top: 1px solid #ddd;
        }
        .total-row {
            font-weight: bold;
            font-size: 16px;
        }
        .footer {
            clear: both;
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>INVOICE</h1>
        <p>Invoice #{{ $invoice->invoice_number }}</p>
        <p>Date: {{ $invoice->created_at->format('F d, Y') }}</p>
    </div>

    <div class="info-section">
        <table>
            <tr>
                <td style="width: 50%;">
                    <strong>Customer Information:</strong><br>
                    {{ $invoice->customer_name }}<br>
                    @if($invoice->customer_phone)
                        Phone: {{ $invoice->customer_phone }}<br>
                    @endif
                    @if($invoice->customer_email)
                        Email: {{ $invoice->customer_email }}
                    @endif
                </td>
                <td style="width: 50%; text-align: right;">
                    <strong>Prepared By:</strong><br>
                    {{ $invoice->user->name }}<br>
                    {{ $invoice->user->email }}
                </td>
            </tr>
        </table>
    </div>

    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 50%;">Product</th>
                <th style="width: 15%; text-align: right;">Unit Price</th>
                <th style="width: 15%; text-align: right;">Quantity</th>
                <th style="width: 20%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td style="text-align: right;">EGP {{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">EGP {{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">EGP {{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount > 0)
                <tr>
                    <td>Discount:</td>
                    <td style="text-align: right; color: red;">-EGP {{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td>Total:</td>
                <td style="text-align: right;">EGP {{ number_format($invoice->total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($invoice->notes)
        <div style="clear: both; margin-top: 30px;">
            <strong>Notes:</strong><br>
            {{ $invoice->notes }}
        </div>
    @endif

    <div class="footer">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
