@extends('layouts.layout')

@section('title', 'Invoice View')

@section('content')
<style>
    .invoice-container {
        max-width: 900px;
        margin: auto;
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px #cbd5e1;
        font-family: 'Arial';
    }
    .invoice-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid #1e40af;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .invoice-title {
        font-size: 32px;
        font-weight: bold;
        color: #1e40af;
        text-transform: uppercase;
    }
    .invoice-table th {
        background: #dbeafe;
        color: #1e3a8a;
        font-weight: bold;
    }
    .totals-table td {
        font-size: 16px;
        padding: 4px 0;
    }
    .grand-total {
        font-size: 20px;
        font-weight: bold;
        color: #1e40af;
    }
    .footer-note {
        margin-top: 30px;
        font-size: 14px;
        color: #475569;
    }
    .signature {
        margin-top: 40px;
        text-align: right;
        color: #1e293b;
    }
</style>

<div class="invoice-container">

    <!-- HEADER -->
    <div class="invoice-header">
        <div>
            <h2 class="invoice-title">INVOICE</h2>
            <p style="margin:0;">Invoice No: <strong>{{ $invoice->invoice_no }}</strong></p>
            <p style="margin:0;">Date: <strong>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</strong></p>
        </div>

        <div style="text-align:right;">
            <h4 style="margin:0; font-weight:bold;">{{ $invoice->supplier->name }}</h4>
            <p style="margin:0; color:#475569;">{{ $invoice->supplier->address ?? '' }}</p>
            <p style="margin:0; color:#475569;">{{ $invoice->supplier->phone ?? '' }}</p>
        </div>
    </div>

    <!-- ITEMS TABLE -->
    <h4 style="color:#1e3a8a; font-weight:bold;">Invoice Items</h4>

    <table class="table table-bordered invoice-table">
        <thead>
            <tr>
                <th>SI.No</th>
                <th>Item Description</th>
                <th class="text-end">Qty</th>
                <th class="text-end">Rate</th>
                <th class="text-end">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->product->name }}</td>
                <td class="text-end">{{ $item->qty }}</td>
                <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                <td class="text-end">₹{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- TOTALS -->
    <div class="row mt-4">
        <div class="col-md-6"></div>

        <div class="col-md-6">
            <table class="table totals-table">
                <tr>
                    <td><strong>Subtotal</strong></td>
                    <td class="text-end">₹{{ number_format($invoice->subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>GST</strong></td>
                    <td class="text-end">₹{{ number_format($invoice->tax, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Discount</strong></td>
                    <td class="text-end">₹{{ number_format($invoice->discount, 2) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Grand Total</td>
                    <td class="text-end">₹{{ number_format($invoice->grand_total, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- FOOTER -->
    <p class="footer-note">Thank you for your business! If you have any questions, contact support.</p>

    

    <a href="{{ route('purchase.invoice.index') }}" class="btn btn-secondary mt-4">← Back</a>

</div>
@endsection
