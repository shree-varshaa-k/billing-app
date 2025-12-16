@extends('layouts.layout')

@section('title', 'Edit Invoice')

@section('content')
<div class="container mt-4">
    <h2>Edit Purchase Invoice</h2>

    <form action="{{ route('purchase.invoice.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Supplier -->
        <div class="mb-3">
            <label class="form-label">Supplier</label>
            <select name="supplier_id" class="form-control" required>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" 
                        {{ $invoice->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Date -->
        <div class="mb-3">
            <label class="form-label">Invoice Date</label>
            <input type="date" name="invoice_date" class="form-control" 
                value="{{ $invoice->invoice_date }}" required>
        </div>

        <hr>

        <!-- Invoice Items -->
        <h4>Invoice Items</h4>

        <table class="table table-bordered" id="itemsTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th width="120">Qty</th>
                    <th width="150">Price</th>
                    <th width="120">GST%</th>
                    <th width="150">Total</th>
                </tr>
            </thead>

            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>
                        <select name="product_id[]" class="form-control">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td><input type="number" name="qty[]" class="form-control qty"
                        value="{{ $item->qty }}" step="0.01"></td>

                    <td><input type="number" name="price[]" class="form-control price"
                        value="{{ $item->price }}" step="0.01"></td>

                    <td><input type="number" name="tax_percent[]" class="form-control tax_percent"
                        value="{{ $item->tax_percent }}" step="0.01"></td>

                    <td><input type="number" class="form-control item_total" 
                        value="{{ $item->total }}" readonly></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <!-- Discount -->
        <div class="mb-3">
            <label class="form-label">Discount Amount</label>
            <input type="number" name="discount" class="form-control" 
                value="{{ $invoice->discount }}" step="0.01">
        </div>

        <button type="submit" class="btn btn-primary">Update Invoice</button>
        <a href="{{ route('purchase.invoice.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

@endsection
