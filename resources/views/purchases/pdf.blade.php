<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Invoice {{ $invoice->invoice_no }}</title>

    <style>
        body { font-family: Arial, sans-serif; color: #333; margin: 0; padding: 0; }
        .container { width: 90%; margin: auto; }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            padding-bottom: 10px;
            border-bottom: 3px solid #007bff;
        }
        .header .title { font-size: 32px; color: #007bff; font-weight: bold; }

        /* Invoice Info */
        .info-box { margin-top: 20px; }
        .info-box p { margin: 5px 0; font-size: 14px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 25px; font-size: 14px; }
        thead { background: #007bff; color: #fff; }
        th, td { padding: 10px; border: 1px solid #e0e0e0; }
        tbody tr:nth-child(even) { background: #f3f6ff; }

        /* Totals */
        .totals {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
        }
        .totals table { width: 100%; }
        .totals td { padding: 8px; }

        /* Payment box */
        .payment-box {
            margin-top: 25px;
            background: #eef4ff;
            padding: 15px;
            border-left: 4px solid #007bff;
            font-size: 14px;
        }

        /* Footer */
        .footer { margin-top: 40px; text-align: center; font-size: 12px; color: gray; }
    </style>
</head>

<body>

<div class="container">

    <!-- Header -->
    <div class="header">
        <div>
           
            
        </div>
        <div class="title">INVOICE</div>
    </div>

    <!-- Invoice Info -->
    <div class="info-box">
        <p><strong>Invoice No:</strong> {{ $invoice->invoice_no }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-m-Y') }}</p>
        <p><strong>Supplier:</strong> {{ $invoice->supplier->name }}</p>
        <p><strong>Address:</strong> {{ $invoice->supplier->address ?? '' }}</p>
    </div>

    <!-- Table -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>GST%</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($invoice->items as $index => $item)
            <tr>
                <td>{{ $index+1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ $item->tax_percent }}%</td>
                <td>{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td><strong>GST:</strong></td>
                <td>{{ number_format($invoice->tax, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Discount:</strong></td>
                <td>{{ number_format($invoice->discount, 2) }}</td>
            </tr>
            <tr>
                <td><strong>Grand Total:</strong></td>
                <td><strong>{{ number_format($invoice->grand_total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    

    <!-- Footer -->
    <div class="footer">
        Thank you for your business! <br>
       
    </div>

</div>

</body>
</html>
