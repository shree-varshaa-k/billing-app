@extends('layouts.layout')

@section('content')

<style>
    /* Hide sidebar, header, footer during print */
    @media print {
        .sidebar,
        .navbar,
        .footer,
        .btn,
        .dropdown,
        .dropdown-menu,
        .no-print {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        .print-area {
            margin: 0;
            padding: 0;
            width: 100%;
        }

        .card {
            box-shadow: none !important;
            border: none !important;
        }
    }
</style>

<div class="container mt-4 print-area">
    <h3 class="fw-bold mb-4">Sale Report</h3>

    {{-- Date Filter Form --}}
    <form method="GET" action="{{ route('reports.sales') }}" class="card p-3 mb-3 shadow-sm no-print">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="fw-semibold">From Date</label>
                <input type="date" name="from_date" value="{{ $from_date }}" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label class="fw-semibold">To Date</label>
                <input type="date" name="to_date" value="{{ $to_date }}" class="form-control" required>
            </div>

            <div class="col-md-4 d-flex align-items-end">
                <button class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">

            <h5 class="text-center mb-4 fw-semibold">
                Sale Summary ({{ $from_date }} to {{ $to_date }})
            </h5>

            <table class="table table-borderless">
                <tr>
                    <td class="fw-bold">Subtotal:</td>
                    <td class="text-end">₹ {{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Total Discount:</td>
                    <td class="text-end">₹ {{ number_format($total_discount, 2) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Total Sold:</td>
                    <td class="text-end">₹ {{ number_format($total_sold, 2) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Customer Paid:</td>
                    <td class="text-end">₹ {{ number_format($customer_paid, 2) }}</td>
                </tr>
                <tr>
                    <td class="fw-bold">Customer Due:</td>
                    <td class="text-end">₹ {{ number_format($customer_due, 2) }}</td>
                </tr>
            </table>

            <div class="text-end mt-4 no-print">
                <button onclick="window.print()" class="btn btn-success">
                    <i class="bi bi-printer"></i> Print
                </button>
            </div>

        </div>
    </div>
</div>

@endsection
