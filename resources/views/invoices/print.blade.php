@extends('layouts.layout')

@section('title', 'Print Invoice')

@section('content')
<style>
/* WATERMARK */
.invoice-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 80px;
    color: rgba(0,0,0,0.05);
    font-weight: 700;

    pointer-events: none;
}

/* BLUE HEADER LIKE SAMPLE IMAGE */
.invoice-header {
    background: #b5ccdaff !important;
    border-radius: 8px;
}

/* BILL TO SECTION */
.bill-to {
    background: #aac2d1ff;
    border-left: 5px solid #0aa7ff;
}

/* TABLE HEADER */
.invoice-table th {
    background: #b1d1e4ff;
    color: #000000ff;
}

/* TOTAL BOX */
.total-box {
    background: #f0f8ff;
    border-radius: 6px;
}

.balance-due {
    background: #0aa7ff;
    color: #fff;
    padding: 10px;
    border-radius: 6px;
}
</style>

<div class="container my-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Invoice #{{ $invoice->id }}</h2>
        <div>
            <a href="{{ route('invoices.index') }}" class="btn btn-secondary me-2">← Back</a>
            <button class="btn btn-primary" onclick="openPrintWindow()">Print Invoice</button>
        </div>
    </div>

    <!-- INVOICE BOX -->
    <div id="invoice-content" class="invoice-wrapper bg-white shadow-sm border rounded p-4 position-relative">

        <!-- WATERMARK -->
        <div class="invoice-watermark">FEATHER SOFTWARES</div>

        <!-- BLUE HEADER -->
        <div class="invoice-header text-white p-4 mb-4">
            <div class="row">
                <div class="col-md-6" style="color:black">
                    <h2 class="fw-bold">Feather Softwares</h2>
                    <p class="mb-1">S.M Arcade Shopping Complex, 2nd Floor</p>
                    <p class="mb-1">Karungal - Marthandam Rd, Marthandam, TN 629165</p>
                    <p class="mb-0">Phone: 075501 10016</p>
                    <p class="mb-0">Website: feathersoftwares.com</p>
                </div>

                <div class="col-md-6 text-end mt-3 mt-md-0" style="color: black;">
                    <p><strong>Date:</strong> {{ $invoice->created_at->format('d-m-Y') }}</p>
                    <p><strong>Invoice #:</strong> {{ $invoice->id }}</p>
                    
                </div>
            </div>
        </div>

        <!-- BILL TO SECTION -->
        <div class="bill-to p-3 mb-4">
            <strong>Bill To:</strong>
            <p class="mb-0">{{ $invoice->client->name }}</p>
        </div>

        <!-- TABLE -->
        <table class="table invoice-table">
            <thead>
                <tr>
                    <th>Quantity</th>
                    <th>Description</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>₹{{ number_format($item->product->price, 2) }}</td>
                    <td>₹{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAL SECTION -->
        <div class="total-box mt-4 p-3">
            <div class="d-flex justify-content-between">
                <strong>Subtotal</strong>
                <span>₹{{ number_format($invoice->total, 2) }}</span>
            </div>
            <div class="d-flex justify-content-between">
                <strong>Tax (0%)</strong>
                <span>₹0.00</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between balance-due">
                <strong>Balance Due</strong>
                <strong>₹{{ number_format($invoice->total, 2) }}</strong>
            </div>
        </div>

        <p class="text-center mt-4">Thank you for your business!</p>
    </div>
</div>

@push('styles')
@endpush

<script>
function openPrintWindow() {
    let content = document.getElementById('invoice-content').innerHTML;
    let printWindow = window.open('', '', 'height=900,width=950');

    printWindow.document.write('<html><head><title>Invoice</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">');
    printWindow.document.write('<style>' + document.querySelector('style').innerHTML + '</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
}
</script>

@endsection
