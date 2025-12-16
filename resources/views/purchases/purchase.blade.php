@extends('layouts.layout')

@section('title', 'Purchase Invoices')

@section('content')
<style>
    /* Card container */
    .table-card {
        background: #ffffff;
        padding: 22px;
        border-radius: 18px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.10);
        transition: 0.3s ease;
    }

    .table-card:hover {
        box-shadow: 0 10px 28px rgba(0,0,0,0.16);
    }

    /* Table styling */
    table th {
        background: #e9f1ff !important;
        font-weight: 700;
        color: #0b5ed7;
    }

    table td {
        vertical-align: middle;
        font-size: 0.95rem;
        font-weight: 500;
    }

    /* Buttons */
    .btn-add {
        border-radius: 12px;
        padding: 8px 18px;
        font-weight: 600;
    }

    .btn-action {
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 0.85rem;
        transition: 0.25s;
    }

    .btn-action:hover {
        transform: scale(1.12);
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

    /* Heading */
    .page-title {
        font-size: 1.7rem;
        font-weight: 700;
        color: #0b5ed7;
    }
</style>

<div class="d-flex justify-content-between mb-3">
    <h3>Purchase Invoices</h3>
    <a href="{{ route('purchase.invoice.create') }}" class="btn btn-primary">+ Add Invoice</a>
</div>

<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>Invoice No</th>
            <th>Supplier</th>
            <th>Date</th>
            <th>Subtotal</th>
            <th>Total</th>
            <th width="200">Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($invoices as $inv)
        <tr>
            <td>{{ $inv->invoice_number ?? $inv->invoice_no }}</td>

            <td>{{ $inv->supplier->name }}</td>
            <td>{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d-m-Y') }}</td>
            <td>₹{{ number_format($inv->subtotal, 2) }}</td>
            <td><strong>₹{{ number_format($inv->grand_total, 2) }}</strong></td>
            <td>
                <div class="d-flex gap-2">
                    <a href="{{ route('purchase.invoice.show', $inv->id) }}" class="btn btn-info btn-sm">View</a>
                    <a href="{{ route('purchase.invoice.edit', $inv->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <a href="{{ route('purchase.invoice.download', $inv->id) }}" class="btn btn-success btn-sm">Download</a>
                    <form action="{{ route('purchase.invoice.delete', $inv->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center">No purchase invoices found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

@endsection
